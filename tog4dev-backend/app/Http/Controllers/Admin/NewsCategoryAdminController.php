<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsCategoryAdminController extends Controller
{
    public function index()
    {
        $data = NewsCategory::orderBy('position', 'ASC')->get();
        return view('admin.news_categories.index', compact('data'));
    }

    public function create()
    {
        return view('admin.news_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'name_en' => 'nullable|string',
            'position' => 'nullable|integer',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['position'] = $validated['position'] ?? 0;

        NewsCategory::create($validated);
        return redirect()->route('news-categories-admin.index')->with('success', __('app.created successfully'));
    }

    public function show($id)
    {
        $data = NewsCategory::findOrFail($id);
        return view('admin.news_categories.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $category = NewsCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'name_en' => 'nullable|string',
            'position' => 'nullable|integer',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        $category->update($validated);
        return redirect()->route('news-categories-admin.index')->with('success', __('app.updated successfully'));
    }

    public function destroy($id)
    {
        $category = NewsCategory::findOrFail($id);
        $category->delete();
        echo json_encode(array("status" => "success"));
    }

    public function change_status($id)
    {
        $category = NewsCategory::findOrFail($id);
        $category->status = !$category->status;
        $category->save();
        echo json_encode(array("status" => "success"));
    }
}
