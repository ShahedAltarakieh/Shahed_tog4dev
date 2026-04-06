<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\OdooService;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        // $response = app(OdooService::class)->post('v1/addCategory', $category->toOdoo(true));

        // if (isset($response['result']['status']) && $response['result']['status'] === 200 && isset($response['result']['data']['id'])) {
        //     $odooId = $response['result']['data']['id'];
        //     $category->odoo_id = $odooId;
        //     $category->saveQuietly(); // prevents triggering updated observer
        // }
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        // app(OdooService::class)->put("v1/updateCategory", $category->toOdoo(false));
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        $data = [
            'params' => [
                'id' => $category->id
            ],
        ];
        app(OdooService::class)->delete("v1/deleteCategory", $data);
    }

    /**
     * Handle the Category "restored" event.
     */
    public function restored(Category $category): void
    {
        //
    }

    /**
     * Handle the Category "force deleted" event.
     */
    public function forceDeleted(Category $category): void
    {
        //
    }
}
