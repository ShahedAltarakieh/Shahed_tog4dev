<?php

namespace App\Jobs\OdooJobs;

use App\Models\QuickContribution;
use App\Services\OdooService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendQuickContributionToOdooJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $contributeId;

    // Maximum retry attempts
    public $tries = 3;

    // Wait 10 seconds between retries
    public function backoff(): array
    {
        return [10]; // seconds
    }

    /**
     * Create a new job instance.
     */
    public function __construct($contributeId)
    {
        $this->contributeId = $contributeId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Reload the full model with relations
        $contribution = QuickContribution::find($this->contributeId);

        if (!$contribution) {
            return;
        }
        
        try {
            if($contribution->odoo_id){
                $response = app(OdooService::class)->put('v1/updateQuickContribute', $contribution->toOdoo(false));
                if (isset($response['result']['status']) && $response['result']['status'] === 200) {
                    $contribution->need_sync = 0;
                    $contribution->saveQuietly();
                } else {
                    Log::error('Updating Odoo Error:', $response->json());
                }
            } else {
                $response = app(OdooService::class)->post('v1/addQuickContribute', $contribution->toOdoo(true));
                if (isset($response['result']['status']) && $response['result']['status'] === 200 && isset($response['result']['data']['id'])) {
                    $odooId = $response['result']['data']['id'];
                    $contribution->odoo_id = $odooId;
                    $contribution->need_sync = 0;
                    $contribution->saveQuietly();
                } else {
                    Log::error('Creating Odoo Error:', $response->json());
                }
            }
        } catch (\Throwable $e) {
            Log::error('Odoo Exception: ' . $e->getMessage());
        }
    }
}
