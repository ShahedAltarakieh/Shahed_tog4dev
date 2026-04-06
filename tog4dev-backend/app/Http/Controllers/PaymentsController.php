<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Payment;
use App\Services\NetworkPaymentService;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;

class PaymentsController extends Controller
{

    protected $networkPaymentService;

    public function __construct(NetworkPaymentService $networkPaymentService)
    {
        $this->networkPaymentService = $networkPaymentService;
    }

    public function index()
    {
        $chart_type = $_GET["chart_type"] ?? 6;

        $list_of_dates = [
            'today' => Carbon::today()->toDateString(),
            'yesterday' => Carbon::yesterday()->toDateString(),
            'this_week_start' => Carbon::now()->startOfWeek(Carbon::SATURDAY)->toDateString(),
            'this_week_end' => Carbon::now()->endOfWeek(Carbon::FRIDAY)->toDateString(),
            'last_week_start' => Carbon::now()->subWeek()->startOfWeek(Carbon::SATURDAY)->toDateString(),
            'last_week_end' => Carbon::now()->subWeek()->endOfWeek(Carbon::FRIDAY)->toDateString(),
            'this_month_start' => Carbon::now()->startOfMonth()->toDateString(),
            'this_month_end' => Carbon::now()->endOfMonth()->toDateString(),
            'last_month_start' => Carbon::now()->subMonth()->startOfMonth()->toDateString(),
            'last_month_end' => Carbon::now()->subMonth()->endOfMonth()->toDateString(),
            'this_year_start' => Carbon::now()->startOfYear()->toDateString(),
            'this_year_end' => Carbon::now()->endOfYear()->toDateString(),
            'last_year_start' => Carbon::now()->subYear()->startOfYear()->toDateString(),
            'last_year_end' => Carbon::now()->subYear()->endOfYear()->toDateString(),
        ];
        $startDate = request('start_date') ?? $list_of_dates["this_year_start"];
        $endDate = request('end_date') ?? $list_of_dates["this_year_end"];

        if (
            !in_array($startDate, $list_of_dates, true) ||
            !in_array($endDate, $list_of_dates, true)
        ) {
            $chart_type = -1;
        }

        $payments_today = $this->getToday();
        $payments_week = $this->getWeek();
        $payments_month = $this->getMonth();
        $payments_year = $this->getYear();
        $all_payments = $this->getAllPayments();
        $payments_custom_range = $this->getCustomRange($startDate, $endDate);

        $firstStartDate = Payment::orderBy('created_at', 'asc')->value('created_at');
        $lastEndDate  = Payment::orderBy('created_at', 'desc')->value('created_at');
	if($firstStartDate){
		$firstStartDate = $firstStartDate->format('Y-m-d');
	} else {
		$firstStartDate = '';
	}
	if($lastEndDate){
		$lastEndDate = $lastEndDate->format('Y-m-d');
	} else {
		$lastEndDate = '';
	}
        $routeName = request()->route()->getName();
        return view('admin.payments.index', compact([
            'routeName',
            'list_of_dates',
            'chart_type',
            'startDate',
            'endDate',
            'payments_today',
            'payments_week',
            'payments_month',
            'payments_year',
            'payments_custom_range',
            'all_payments',
            'firstStartDate',
            'lastEndDate'
        ]));
    }

    public function fetch_data(Request $request)
    {
        $routeName = request()->route()->getName();
        $statusFilter = match ($routeName) {
            'payments.fetch_data' => ['approved'],
            'refunds.index'  => ['refund']
        };

        // Base query with eager loading
        $query = Payment::with(['userDetails', 'influencer'])->whereIn('status', $statusFilter);

        // Date filtering if both start_date and end_date provided
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $type = $request->input('chart_type');
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }
        $sum_custom_payments = 0;
        // If type=8, return sum of payments in date range
        if ($type == 8) {
            $sum_custom_payments = $this->getCustomRange($startDate, $endDate);
        }

        // Search logic
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                    ->orWhere('cart_id', 'like', "%{$search}%")
                    ->orWhere('name_on_card', 'like', "%{$search}%")
                    ->orWhere('bank_issuer', 'like', "%{$search}%")
                    ->orWhereHas('userDetails', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('influencer', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Column mapping for sorting
        $columns = [
            'id',
            'userDetails.first_name',
            'email',
            'phone',
            'status',
            'cart_id',
            'influencer.name',
            'amount',
            'created_at',
            'name_on_card',
            'bank_issuer',
            'action',
        ];

        // Sorting logic
        if ($request->has('order.0')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columnName = $columns[$orderColumnIndex] ?? 'id';

            if ($columnName === 'userDetails.first_name') {
                $query->join('users as u', 'payments.user_id', '=', 'u.id')
                    ->orderBy('u.first_name', $orderDir)
                    ->select('payments.*');
            } elseif ($columnName === 'email') {
                $query->join('users as u', 'payments.user_id', '=', 'u.id')
                    ->orderBy('u.email', $orderDir)
                    ->select('payments.*');
            } elseif ($columnName === 'phone') {
                $query->join('users as u', 'payments.user_id', '=', 'u.id')
                    ->orderBy('u.phone', $orderDir)
                    ->select('payments.*');
            } elseif ($columnName === 'influencer.name') {
                $query->leftJoin('influencers as i', 'payments.influencer_id', '=', 'i.id')
                    ->orderBy('i.name', $orderDir)
                    ->select('payments.*');
            } elseif ($columnName === 'created_at') {
                $query->orderBy('created_at', $orderDir);
            } else {
                $query->orderBy($columnName, $orderDir);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Count totals (cloned for filtering)
        $total = Payment::whereIn('status', $statusFilter)->count();
        $filtered = (clone $query)->count();

        // Fetch data with pagination
        $data = $query
            ->skip($request->input('start'))
            ->take($request->input('length'))
            ->get();

        // Format response
        $formatted = $data->map(function ($payment) {
            $viewUrl = route('payments.show', $payment->id);

            $refundButton = '';
            if ($payment->status !== 'refund') {
                $refundButton = '<img src="' . asset('img/refund.png') . '" 
                                    style="width:30px;cursor:pointer" 
                                    class="mx-2 btn-refund"
                                    data-payment-id="' . $payment->id . '" 
                                    data-url="' . route('payment.refund') . '">';
            }

            $viewButton = '<a class="btn btn-primary" href="' . $viewUrl . '">
                                <i class="mdi mdi-eye-outline"></i>
                            </a>';

            return [
                'id' => $payment->id,
                'user_name' => optional($payment->userDetails)->first_name . ' ' . optional($payment->userDetails)->last_name,
                'email' => optional($payment->userDetails)->email,
                'phone' => optional($payment->userDetails)->phone,
                'status' => $payment->status,
                'cart_id' => $payment->cart_id,
                'influencer' => $payment->influencer ? $payment->influencer->name : 'Website',
                'amount' => (new \App\Helpers\Helper)->formatNumber($payment->amount) . __('app.currency'),
                'created_at' => date("Y-m-d", strtotime($payment->created_at)),
                'name_on_card' => $payment->name_on_card,
                'bank_issuer' => $payment->bank_issuer,
                'action' => $refundButton . $viewButton,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $formatted,
            'sum_custom_payments' => $sum_custom_payments
        ]);
    }



    public function show($id)
    {
        // Retrieve the payment with cart items for the specific payment ID
        $payment = Payment::with(['cartItems.model', 'cartItems.dedications', 'user'])->findOrFail($id);
        // Pass the payment details to the Blade view
        return view('admin.payments.show', compact('payment'));
    }

    public function refund(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
        ]);

        try {
            $refundResponse = $this->networkPaymentService->refundPayment($request->payment_id);

            return response()->json([
                'status' => "success",
                'message' => 'Refund processed successfully',
                'data' => $refundResponse,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Refund failed: ' . $e->getMessage(),
            ], 400);
        }
    }

    public function downloadCsv()
    {
        return Excel::download(new PaymentsExport(), 'payments.xlsx');        
    }

        public function getToday()
    {
        // Define today and yesterday's start & end times
        $todayStart = Carbon::today()->startOfDay(); // 00:00:00
        $todayEnd = Carbon::today()->endOfDay(); // 23:59:59
    
        $yesterdayStart = Carbon::yesterday()->startOfDay();
        $yesterdayEnd = Carbon::yesterday()->endOfDay();
    
        // Get payments for today
        $payments_today = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->sum('amount');
    
        // Get payments for yesterday
        $payments_yesterday = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$yesterdayStart, $yesterdayEnd])
            ->sum('amount');
    
        // Calculate percentage change
        if ($payments_yesterday > 0) {
            $percentage_change = (($payments_today - $payments_yesterday) / $payments_yesterday) * 100;
        } else {
            $percentage_change = $payments_today > 0 ? 100 : 0; // Avoid division by zero
        }
    
        return [
            'today' => $payments_today,
            'yesterday' => $payments_yesterday,
            'percentage_change' => round($percentage_change, 2)
        ];
    }

    public function getWeek(){
        // Define start and end of this week (Saturday - Friday)
        $thisWeekStart = Carbon::now()->startOfWeek(Carbon::SATURDAY);
        $thisWeekEnd = $thisWeekStart->copy()->addDays(6)->endOfDay();

        // Define start and end of last week (Saturday - Friday)
        $lastWeekStart = $thisWeekStart->copy()->subWeek();
        $lastWeekEnd = $lastWeekStart->copy()->addDays(6)->endOfDay();

        // Get this week's approved payments
        $payments_this_week = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$thisWeekStart, $thisWeekEnd])
            ->sum('amount');

        // Get last week's approved payments
        $payments_last_week = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->sum('amount');

        // Calculate percentage change
        if ($payments_last_week > 0) {
            $percentage_change = (($payments_this_week - $payments_last_week) / $payments_last_week) * 100;
        } else {
            $percentage_change = $payments_this_week > 0 ? 100 : 0; // Avoid division by zero
        }

        // Return results
        return [
            'this_week' => $payments_this_week,
            'last_week' => $payments_last_week,
            'percentage_change' => round($percentage_change, 2),
        ];

    }

    public function getMonth()
    {
        // Define start & end of this month
        $thisMonthStart = Carbon::now()->startOfMonth(); // First day of this month, 00:00:00
        $thisMonthEnd = Carbon::now()->endOfMonth(); // Last day of this month, 23:59:59

        // Define start & end of last month
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Get payments for this month
        $payments_this_month = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$thisMonthStart, $thisMonthEnd])
            ->sum('amount');

        // Get payments for last month
        $payments_last_month = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('amount');

        // Calculate percentage change
        if ($payments_last_month > 0) {
            $percentage_change = (($payments_this_month - $payments_last_month) / $payments_last_month) * 100;
        } else {
            $percentage_change = $payments_this_month > 0 ? 100 : 0; // Avoid division by zero
        }

        return [
            'this_month' => $payments_this_month,
            'last_month' => $payments_last_month,
            'percentage_change' => round($percentage_change, 2)
        ];
    }

    public function getYear()
    {
        // Define start & end of this year
        $thisYearStart = Carbon::now()->startOfYear(); // January 1st, 00:00:00
        $thisYearEnd = Carbon::now()->endOfYear(); // December 31st, 23:59:59

        // Define start & end of last year
        $lastYearStart = Carbon::now()->subYear()->startOfYear();
        $lastYearEnd = Carbon::now()->subYear()->endOfYear();

        // Get payments for this year
        $payments_this_year = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$thisYearStart, $thisYearEnd])
            ->sum('amount');

        // Get payments for last year
        $payments_last_year = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$lastYearStart, $lastYearEnd])
            ->sum('amount');

        // Calculate percentage change
        if ($payments_last_year > 0) {
            $percentage_change = (($payments_this_year - $payments_last_year) / $payments_last_year) * 100;
        } else {
            $percentage_change = $payments_this_year > 0 ? 100 : 0; // Avoid division by zero
        }

        return [
            'this_year' => $payments_this_year,
            'last_year' => $payments_last_year,
            'percentage_change' => round($percentage_change, 2)
        ];
    }

    public function getCustomRange($start, $end)
    {
        $startDate = Carbon::parse($start)->startOfDay();
        $endDate   = Carbon::parse($end)->endOfDay();
    
        // Get payments for this year
        $payments = Payment::where('status', 'approved')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        return $payments;
    }

    public function getAllPayments()
    {
        $payments = Payment::where('status', 'approved')
            ->sum('amount');

        return $payments;
    }
}
