<?php

namespace App\Console\Commands;

use App\Mail\ErrorEmail;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderSubscriptionEmail; // Ensure this matches your mailable class

class SendReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-send-reminder-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for subscriptions ending in 2 days';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentDate = Carbon::now();
        $targetDate = $currentDate->copy()->addDays(2);
        $subscriptions = Subscription::where('status', 'active')
            ->where("send_reminder", 0)
            ->where('id', '>=', 884)
            ->where('end_date', '>=', $targetDate->startOfDay())
            ->where('end_date', '<', $currentDate->addDays(2)->endOfDay())
            ->get();

        foreach ($subscriptions as $subscription) {
            try {
                if ($subscription->user && $subscription->payment) {
                    if($subscription->payment->payment_type == "Network"){
                        Mail::to($subscription->user->email)->cc(env('BILLS_EMAIL'))->send(new ReminderSubscriptionEmail($subscription));
                        $this->info('Reminder email sent to: ' . $subscription->user->email);
                        $subscription->update([
                            "send_reminder" => 1,
                        ]);
                    }
                } else {
                    Mail::to(env('ERROR_EMAIL'))->send(new ErrorEmail(null, $subscription));
                }
            } catch (\Exception $e) {
                $this->error("Error processing payment {$subscription->id}: " . $e->getMessage());
            }
        }
        return 0;
    }
}
