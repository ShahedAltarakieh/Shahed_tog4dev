<?php

namespace App\Observers;

use App\Models\QuickContribution;
use App\Services\OdooService;

class QuickContributionObserver
{
    /**
     * Handle the QuickContribution "created" event.
     */
    public function created(QuickContribution $quickContribution): void
    {
        // // Load the related prices
        // $quickContribution->load('prices');
        // $quickContribution = QuickContribution::find(34);
        // $response = app(OdooService::class)->post('v1/addQuickContribute', $quickContribution->toOdoo(true));

        // if (isset($response['result']['status']) && $response['result']['status'] === 200 && isset($response['result']['data']['id'])) {
        //     $odooId = $response['result']['data']['id'];
        //     $quickContribution->odoo_id = $odooId;
        //     $quickContribution->saveQuietly(); // prevents triggering updated observer
        // }
    }

    /**
     * Handle the QuickContribution "updated" event.
     */
    public function updated(QuickContribution $quickContribution): void
    {
        // app(OdooService::class)->put("v1/updateQuickContribute", $quickContribution->toOdoo(false));
    }

    /**
     * Handle the QuickContribution "deleted" event.
     */
    public function deleted(QuickContribution $quickContribution): void
    {
        $data = [
            'params' => [
                'id' => $quickContribution->id
            ],
        ];
        app(OdooService::class)->delete("v1/deleteQuickContribute", $data);
    }

    /**
     * Handle the QuickContribution "restored" event.
     */
    public function restored(QuickContribution $quickContribution): void
    {
        //
    }

    /**
     * Handle the QuickContribution "force deleted" event.
     */
    public function forceDeleted(QuickContribution $quickContribution): void
    {
        //
    }
}
