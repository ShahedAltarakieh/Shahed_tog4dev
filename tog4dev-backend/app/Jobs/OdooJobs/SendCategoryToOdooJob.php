<?php

namespace App\Jobs\OdooJobs;

use App\Models\Category;
use App\Services\OdooService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendCategoryToOdooJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $categoryId;

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
    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Reload the full model with relations
        $category = Category::find($this->categoryId);

        if (!$category) {
            return;
        }
        
        try {
            if($category->odoo_id){
                $response = app(OdooService::class)->put('v1/updateCategory', $category->toOdoo(false));
                if (isset($response['result']['status']) && $response['result']['status'] === 200) {
                    $category->need_sync = 0;
                    $category->saveQuietly();
                } else {
                    Log::error('Updating Odoo Error:', $response->json());
                }
            } else {
                $response = app(OdooService::class)->post('v1/addCategory', $category->toOdoo(true));
                if (isset($response['result']['status']) && $response['result']['status'] === 200 && isset($response['result']['data']['id'])) {
                    $odooId = $response['result']['data']['id'];
                    $category->odoo_id = $odooId;
                    $category->need_sync = 0;
                    $category->saveQuietly();
                } else {
                    Log::error('Creating Odoo Error:', $response->json());
                }
            }
        } catch (\Throwable $e) {
            Log::error('Odoo Exception: ' . $e->getMessage());
        }
    }
}
