<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageMaintenance;
use Illuminate\Http\Request;

class PageMaintenanceController extends Controller
{
    public function index()
    {
        $items = PageMaintenance::orderBy('order')->orderBy('id')->get();
        return view('admin.page_maintenance.index', compact('items'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'items'                  => 'required|array',
            'items.*.id'             => 'required|integer|exists:page_maintenance,id',
            'items.*.is_under_update'=> 'nullable',
            'items.*.message_en'     => 'nullable|string|max:2000',
            'items.*.message_ar'     => 'nullable|string|max:2000',
            'items.*.starts_at'      => 'nullable|date',
            'items.*.ends_at'        => 'nullable|date|after_or_equal:items.*.starts_at',
        ]);

        foreach ($request->input('items', []) as $row) {
            $item = PageMaintenance::find($row['id']);
            if ($item) {
                $item->update([
                    'is_under_update' => filter_var($row['is_under_update'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'message_en'      => $row['message_en'] ?? null,
                    'message_ar'      => $row['message_ar'] ?? null,
                    'starts_at'       => !empty($row['starts_at']) ? $row['starts_at'] : null,
                    'ends_at'         => !empty($row['ends_at']) ? $row['ends_at'] : null,
                ]);
            }
        }

        return redirect()->route('page-maintenance.index')->with('success', __('app.updated successfully'));
    }
}
