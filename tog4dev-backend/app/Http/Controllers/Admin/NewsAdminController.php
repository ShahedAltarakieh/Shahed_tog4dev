<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsAdminController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per-page', 20);
        $news = News::with('category')
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);

        return response()->json(['data' => $news]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'title_en' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'excerpt_en' => 'nullable|string',
            'body' => 'nullable|string',
            'body_en' => 'nullable|string',
            'news_category_id' => 'nullable|exists:news_categories,id',
            'is_featured' => 'boolean',
            'status' => 'boolean',
            'published_at' => 'nullable|date',
            'position' => 'integer',
        ]);

        $news = News::create($validated);

        if ($request->hasFile('image')) {
            $news->addMediaFromRequest('image')->toMediaCollection('news');
        }

        return response()->json(['data' => $news, 'message' => 'News created successfully.'], 201);
    }

    public function show($id)
    {
        $news = News::with('category', 'media')->findOrFail($id);
        return response()->json(['data' => $news]);
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string',
            'title_en' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'excerpt_en' => 'nullable|string',
            'body' => 'nullable|string',
            'body_en' => 'nullable|string',
            'news_category_id' => 'nullable|exists:news_categories,id',
            'is_featured' => 'boolean',
            'status' => 'boolean',
            'published_at' => 'nullable|date',
            'position' => 'integer',
        ]);

        $news->update($validated);

        if ($request->hasFile('image')) {
            $news->clearMediaCollection('news');
            $news->addMediaFromRequest('image')->toMediaCollection('news');
        }

        return response()->json(['data' => $news, 'message' => 'News updated successfully.']);
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return response()->json(['message' => 'News deleted successfully.']);
    }
}
