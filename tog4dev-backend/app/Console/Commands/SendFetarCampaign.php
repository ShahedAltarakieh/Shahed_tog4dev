<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Mail\FetarCampaignMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendFetarCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-fetar-campaign';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Fetar Campaign emails';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Using Cache to create a mutex lock manually if needed
        $lockKey = 'process_fetar_campaign_lock';

        if (Cache::has($lockKey)) {
            $this->info('Process is already running, skipping this execution.');
            return;
        }

        // Lock the job to prevent overlapping
        Cache::put($lockKey, true, 2); // Lock for 2 minutes
        
        $emails = User::where('send_email', 0)->take(95)->get();

        foreach($emails as $email){
            try {
                Mail::mailer('info')->to($email->email)->send(new FetarCampaignMail());
                // if no exception, mark as sent
                $email->send_email = 1;
            } catch (\Exception $e) {
                // if sending fails, mark as -1
                $email->send_email = -1;
            }
            $email->update();
        }
    }
}
