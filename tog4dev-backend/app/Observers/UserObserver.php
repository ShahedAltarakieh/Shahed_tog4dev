<?php

namespace App\Observers;

use App\Models\User;
use App\Services\OdooService;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // $response = app(OdooService::class)->post('v1/addPartner', $user->toOdoo(true));

        // if (isset($response['result']['status']) && $response['result']['status'] === 200 && isset($response['result']['data']['id'])) {
        //     $odooId = $response['result']['data']['id'];
        //     $user->odoo_id = $odooId;
        //     $user->saveQuietly(); // prevents triggering updated observer
        // }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // app(OdooService::class)->put("v1/updatePartner", $user->toOdoo(false));
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
