<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\NavSetting;

class NavSettingApiController extends Controller
{
    public function index()
    {
        $items = NavSetting::orderBy('order')->orderBy('id')->get([
            'page_key', 'label_en', 'label_ar', 'visible', 'order',
        ]);

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }
}
