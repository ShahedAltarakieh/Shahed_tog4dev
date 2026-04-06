<?php

namespace App\Observers;

use App\Models\Item;
use App\Services\OdooService;

class ItemObserver
{
    /**
     * Handle the Item "created" event.
     */
    public function created(Item $item): void
    {
        // $response = app(OdooService::class)->post('v1/addItems', $item->toOdoo(true));

        // if (isset($response['result']['status']) && $response['result']['status'] === 200 && isset($response['result']['data']['id'])) {
        //     $odooId = $response['result']['data']['id'];
        //     $item->odoo_id = $odooId;
        //     $item->saveQuietly(); // prevents triggering updated observer
        // }
    }

    /**
     * Handle the Item "updated" event.
     */
    public function updated(Item $item): void
    {
        // app(OdooService::class)->put('v1/updateItem', $item->toOdoo(false));
    }

    /**
     * Handle the Item "deleted" event.
     */
    public function deleted(Item $item): void
    {
        $data = [
            'params' => [
                'id' => $item->id
            ],
        ];
        app(OdooService::class)->delete('v1/deleteItem', $data);
    }

    public function restored(Item $item): void
    {
        // Optionally handle restore
    }

    public function forceDeleted(Item $item): void
    {
        // Optionally handle force delete
    }
} 