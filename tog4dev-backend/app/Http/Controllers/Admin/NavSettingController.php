<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavSetting;
use Illuminate\Http\Request;

class NavSettingController extends Controller
{
    public function index()
    {
        $items = NavSetting::orderBy('order')->orderBy('id')->get();
        return view('admin.nav_settings.index', compact('items'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:nav_settings,id',
            'items.*.visible' => 'nullable',
            'items.*.order' => 'nullable|integer',
        ]);

        foreach ($request->input('items', []) as $row) {
            $item = NavSetting::find($row['id']);
            if ($item) {
                $item->update([
                    'visible' => filter_var($row['visible'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'order'   => isset($row['order']) ? (int) $row['order'] : $item->order,
                ]);
            }
        }

        return redirect()->route('nav-settings.index')->with('success', __('app.updated successfully'));
    }
}
