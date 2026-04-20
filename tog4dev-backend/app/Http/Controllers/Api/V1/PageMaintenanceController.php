<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PageMaintenance;

class PageMaintenanceController extends Controller
{
    public function index()
    {
        $now = now();
        $items = PageMaintenance::where('is_under_update', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->get()
            ->map(function ($p) {
                return [
                    'page_key'   => $p->page_key,
                    'label_en'   => $p->label_en,
                    'label_ar'   => $p->label_ar,
                    'message_en' => $p->message_en,
                    'message_ar' => $p->message_ar,
                    'starts_at'  => optional($p->starts_at)->toIso8601String(),
                    'ends_at'    => optional($p->ends_at)->toIso8601String(),
                ];
            });

        return response()->json(['data' => $items]);
    }
}
