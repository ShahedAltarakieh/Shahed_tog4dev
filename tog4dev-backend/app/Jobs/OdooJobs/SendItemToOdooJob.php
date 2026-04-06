<?php

namespace App\Jobs\OdooJobs;

use App\Models\Item;
use App\Services\OdooService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendItemToOdooJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $itemId;

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
    public function __construct($itemId)
    {
        $this->itemId = $itemId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Reload the full model with relations
        $item = Item::find($this->itemId);

        if (!$item) {
            return;
        }
        
        try {
            if($item->odoo_id){
                $response = app(OdooService::class)->put('v1/updateItem', $item->toOdoo(false));
                if (isset($response['result']['status']) && $response['result']['status'] === 200) {
                    $item->need_sync = 0;
                    $item->saveQuietly();
                } else {
                    Log::error('Updating Odoo Error:', $response->json());
                }
            } else {
                $response = app(OdooService::class)->post('v1/addItems', $item->toOdoo(true));
                if (isset($response['result']['status']) && $response['result']['status'] === 200 && isset($response['result']['data']['id'])) {
                    $odooId = $response['result']['data']['id'];
                    $item->odoo_id = $odooId;
                    $item->need_sync = 0;
                    $item->saveQuietly();
                } else {
                    Log::error('Creating Odoo Error:', $response->json());
                }
            }
        } catch (\Throwable $e) {
            Log::error('Odoo Exception: ' . $e->getMessage());
        }
    }
}
