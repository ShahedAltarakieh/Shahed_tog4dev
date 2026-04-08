<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsCategoryAdminController extends Controller
{
    public function index()
    {
        $categories = NewsCategory::orderBy('position', 'ASC')->get();
        return response()->json(['data' => $categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'name_en' => 'nullable|string',
            'status' => 'boolean',
            'position' => 'integer',
        ]);

        $category = NewsCategory::create($validated);
        return response()->json(['data' => $category, 'message' => 'Category created successfully.'], 201);
    }

    public function show($id)
    {
        $category = NewsCategory::findOrFail($id);
        return response()->json(['data' => $category]);
    }

    public function update(Request $request, $id)
    {
        $category = NewsCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'name_en' => 'nullable|string',
            'status' => 'boolean',
            'position' => 'integer',
        ]);

        $category->update($validated);
        return response()->json(['data' => $category, 'message' => 'Category updated successfully.']);
    }

    public function destroy($id)
    {
        $category = NewsCategory::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully.']);
    }
}
