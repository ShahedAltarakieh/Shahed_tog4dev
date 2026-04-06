<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\PhoneHelper;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\EfwateercomInquiry;
use App\Models\EfwateercomService;
use App\Models\Payment;
use App\Models\PaymentUserDetail;
use App\Models\PriceOption;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EfawateercomController extends Controller
{
    /**
     * Bill / payment inquiry (eFawateercom → merchant).
     *
     * Query: mobileNumber, parentId (string id on efwateercom_services; rejects rows with parent_id set).
     */
    public function inquiry(Request $request): JsonResponse
    {
        $validator = Validator::make($request->query(), [
            'mobileNumber' => 'required|string',
            'parentId' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $mobileNumber = $request->query('mobileNumber');
        $parentId = trim((string) $request->query('parentId'));

        Log::info('Efawateercom inquiry', [
            'mobileNumber' => $mobileNumber,
            'parentId' => $parentId,
        ]);

        $parentService = EfwateercomService::where('parent_id', $parentId)->first();

        if (! $parentService) {
            return response()->json([
                'success' => false,
                'message' => 'Parent service not found',
            ], 404);
        }

        $user = $this->findUserByMobileNumber((string) $mobileNumber);
        $name = $user
            ? trim((string) $user->first_name.' '.(string) $user->last_name)
            : '';

        $inquiry = EfwateercomInquiry::create([
            'mobile_number' => $mobileNumber,
            'parent_id' => $parentId,
            'user_id' => $user?->id,
            'customer_name' => $name !== '' ? $name : null,
        ]);

        return response()->json([
            'name' => $name,
            'ref_num' => $inquiry->id,
        ], 200);
    }

    protected function findUserByMobileNumber(string $mobileNumber): ?User
    {
        $raw = trim($mobileNumber);
        $inputForHelper = $raw;
        if ($inputForHelper !== '' && str_starts_with($inputForHelper, '0') && ! str_starts_with($inputForHelper, '00')) {
            $inputForHelper = substr($inputForHelper, 1);
        }
        if ($inputForHelper !== '' && ! str_starts_with($inputForHelper, '+962')) {
            if (str_starts_with($inputForHelper, '962')) {
                $inputForHelper = '+'.$inputForHelper;
            } else {
                $inputForHelper = '+962'.$inputForHelper;
            }
        }

        $candidates = array_unique(array_filter([
            $raw,
            $inputForHelper !== $raw ? $inputForHelper : null,
            PhoneHelper::getPhoneDetails($inputForHelper)['phone'] ?? null,
        ]));

        foreach ($candidates as $phone) {
            $user = User::where('phone', $phone)->first();
            if ($user) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Payment notification / confirmation (eFawateercom → merchant).
     *
     * Query: mobileNumber, parentId, serviceID, paidAmount, transactionId, payer, ref_num
     * When creating a payment, loads inquiry by ref_num + parentId, resolves efw service, then updates
     * that inquiry’s service_type and efwateercom_service_id inside the same DB transaction.
     */
    public function receivePayment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->query(), [
            'mobileNumber' => 'required|string',
            'parentId' => 'required|string',
            'serviceID' => 'required|string',
            'paidAmount' => 'required',
            'transactionId' => 'required|string',
            'payer' => 'nullable',
            'ref_num' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $mobileNumber = (string) $request->query('mobileNumber');
        $parentId = trim((string) $request->query('parentId'));
        $serviceID = trim((string) $request->query('serviceID'));
        $paidAmount = (float) $request->query('paidAmount');
        $transactionId = (string) $request->query('transactionId');
        $refNum = (int) $request->query('ref_num');
        $payer = $request->query('payer');
        $payerData = is_array($payer) ? $payer : (is_string($payer) ? json_decode($payer, true) : null);
        if (! is_array($payerData)) {
            $payerData = ['raw' => $payer];
        }

        Log::info('Efawateercom receivePayment', [
            'mobileNumber' => $mobileNumber,
            'parentId' => $parentId,
            'serviceID' => $serviceID,
            'transactionId' => $transactionId,
            'ref_num' => $refNum,
            'paidAmount' => $paidAmount,
        ]);

        $data = array_merge($request->query(), [
            'paidAmount' => $paidAmount,
            'payer' => $payerData,
        ]);

        $payment = Payment::query()
            ->where('payment_type', 'Efawateercom')
            ->where(function ($q) use ($transactionId) {
                $q->where('cart_id', $transactionId)
                    ->orWhere('efwateercom_payment_number', $transactionId);
            })
            ->first();

        $approvedByRef = Payment::query()
            ->where('payment_type', 'Efawateercom')
            ->where('efwateercom_payment_number', (string) $refNum)
            ->where('status', 'approved')
            ->first();

        if ($approvedByRef) {
            return response()->json([
                'success' => true,
                'status' => 'approved',
                'message' => 'Payment already approved',
            ], 200);
        }

        if ($payment && $payment->status === 'approved') {
            return response()->json([
                'success' => true,
                'status' => 'approved',
                'message' => 'Payment already approved',
            ], 200);
        }

        if (!$payment) {
            $inquiry = EfwateercomInquiry::query()
                ->whereKey($refNum)
                ->where('parent_id', $parentId)
                ->first();

            if (! $inquiry) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inquiry reference not found',
                ], 404);
            }

            $efwService = EfwateercomService::where('parent_id', $parentId)->first();
            if (! $efwService) {
                $efwService = EfwateercomService::where('service_type', $serviceID)->first();
            }

            if (! $efwService) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service not found',
                ], 404);
            }

            $user = $this->findUserByMobileNumber($mobileNumber);

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }

            try {
                $payment = Payment::query()->getModel()->getConnection()->transaction(function () use ($transactionId, $paidAmount, $efwService, $user, $data, $refNum, $inquiry, $serviceID) {
                    $inquiry->update([
                        'service_type' => $serviceID,
                        'efwateercom_service_id' => $efwService->id,
                    ]);

                    $serviceModel = $efwService->model;
                    $priceOption = null;
                    if (! empty($efwService->option_id)) {
                        $priceOption = PriceOption::find($efwService->option_id);
                    }

                    $newPayment = Payment::create([
                        'user_id' => $user->id,
                        'cart_id' => $transactionId,
                        'status' => 'initiated',
                        'amount' => $paidAmount,
                        'payment_type' => 'Efawateercom',
                        'efwateercom_payment_number' => $refNum,
                        'country' => 'JOR',
                        'temp_id' => null,
                        'lang' => 'ar',
                        'response' => $data,
                    ]);

                    Cart::create([
                        'user_id' => $user->id,
                        'item_id' => $efwService->model_id,
                        'model_type' => $efwService->model_type,
                        'payment_id' => $newPayment->id,
                        'price' => $paidAmount,
                        'type' => 'one_time',
                        'is_paid' => false,
                        'quantity' => 1,
                        'title' => $serviceModel->title ?? null,
                        'title_en' => $serviceModel->title_en ?? null,
                        'description' => $serviceModel->description ?? null,
                        'description_en' => $serviceModel->description_en ?? null,
                        'location' => $serviceModel->location ?? null,
                        'location_en' => $serviceModel->location_en ?? null,
                        'analyticـaccount_id' => $serviceModel->{'analyticـaccount'} ?? null,
                        'has_beneficiary' => $serviceModel->has_beneficiary ?? false,
                        'option_id' => $priceOption?->id,
                        'option_label' => $priceOption ? json_encode([
                            'd1_option' => $priceOption->d1_option,
                            'd1_option_en' => $priceOption->d1_option_en,
                            'd2_option' => $priceOption->d2_option,
                            'd2_option_en' => $priceOption->d2_option_en,
                        ]) : null,
                    ]);

                    return $newPayment;
                });
            } catch (\Throwable $e) {
                Log::error('Efawateercom receivePayment create failed', [
                    'exception' => $e->getMessage(),
                    'transactionId' => $transactionId,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Could not create payment',
                ], 500);
            }
        }

        $payment->cart_id = $transactionId;
        $payment->efwateercom_payment_number = $refNum;
        $payment->country = 'JOR';
        $payment->response = array_merge((array) $payment->response, $data);

        $detailUser = $this->findUserByMobileNumber($mobileNumber) ?: User::find($payment->user_id);
        if ($detailUser) {
            $phoneDetails = PhoneHelper::getPhoneDetails($mobileNumber);
            PaymentUserDetail::updateOrCreate(
                ['payment_id' => $payment->id],
                [
                    'user_id' => $detailUser->id,
                    'first_name' => $detailUser->first_name,
                    'last_name' => $detailUser->last_name,
                    'email' => $detailUser->email,
                    'phone' => $phoneDetails['phone'] ?? $mobileNumber,
                    'country' => $phoneDetails['country'] ?? $detailUser->country,
                ]
            );
        }

        $payment->status = 'approved';
        $payment->save();

        Cart::where('payment_id', $payment->id)
            ->where('user_id', $payment->user_id)
            ->where('is_paid', false)
            ->update(['is_paid' => true]);

        $payment->save();

        return response()->json([
            'success' => true,
            'status' => 'paid',
            'ref_id' => $payment->id,
        ], 200);
    }
}
