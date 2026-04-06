<?php

namespace App\Http\Controllers;

use App\Mail\SendUnsubscriptionMail;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

class SubscriptionsController extends Controller
{
    public function index($active = "active")
    {
        $subscriptions = Subscription::with('user', 'payment' ,'payment.influencer')
        ->whereNotNull('end_date') // Add this condition
        ->where("status", $active)
        ->orderBy('created_at', 'desc')
        ->get();

        // Pass the data to the Blade view
        return view('admin.subscriptions.index', compact('subscriptions', 'active'));
    }

    public function downloadCsv($active = "active")
    {
        $subscriptions = Subscription::with('item','payment','user', 'payment.influencer')
            ->whereNotNull('end_date') // Add this condition
            ->where("status", $active)
            ->orderBy('created_at', 'desc')
            ->get();

        $title = "Subscriptions - ".$active;

        // Define the headers for the CSV
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$title.'.csv"',
        ];

        // Data to be written to the CSV
        $list = [
            [   '#',
                'Customer',
                'Phone',
                'E-mail',
                'Country',
                'Transaction ID',
                'Item',
                'Amount',
                'Start Date',
                'Renew Date',
                'Created At',
                'Influencer Name',
                'Payment Method'
            ], // Header row
        ];

        foreach($subscriptions as $item){
            $title = $item->title_en;

            $list[] = [
                $item->id,
                ($item->payment->userDetails->first_name ?? $item->user->first_name)." ".($item->payment->userDetails->last_name ?? $item->user->last_name),
                (string) $item->user->phone,
                $item->user->email,
                $item->payment->userDetails->email ?? $item->user->email,
                $item->payment->userDetails->country ?? $item->user->country,
                $item->payment->cart_id,
                $title,
                $item->price . __('app.currency'),
                $item->start_date,
                $item->end_date,
                $item->created_at->format('Y-m-d H:i:s'),
                $item->payment->influencer ? $item->payment->influencer->name : 'Website',
                ($item->payment->token == "apple") ? "Apple Pay": "VISA/MASTERCARD",
            ];
        }

        // Open a file pointer in memory
        $callback = function () use ($list) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            foreach ($list as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function deactivate($id)
    {
        $subscription = Subscription::find($id);
        $subscription->status = 'inactive';
        $subscription->save();

        if ($subscription) {
            Mail::to(users: $subscription->user->email)->cc(env('BILLS_EMAIL'))->send(new SendUnsubscriptionMail($subscription));
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
}
