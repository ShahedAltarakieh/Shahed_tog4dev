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
        $data = News::with('category')
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('admin.news.index', compact('data'));
    }

    public function create()
    {
        $categories = NewsCategory::getActive()->orderBy('position', 'ASC')->get();
        return view('admin.news.create', compact('categories'));
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
            'news_category_id' => 'required|exists:news_categories,id',
            'published_at' => 'nullable|date',
            'position' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_tablet' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_mobile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['position'] = $validated['position'] ?? 0;
        unset($validated['image'], $validated['image_tablet'], $validated['image_mobile']);

        $news = News::create($validated);

        if ($request->hasFile('image')) {
            $news->addMediaFromRequest('image')->toMediaCollection('news');
        }
        if ($request->hasFile('image_tablet')) {
            $news->addMediaFromRequest('image_tablet')->toMediaCollection('news_tablet');
        }
        if ($request->hasFile('image_mobile')) {
            $news->addMediaFromRequest('image_mobile')->toMediaCollection('news_mobile');
        }

        return redirect()->route('news-admin.index')->with('success', __('app.created successfully'));
    }

    public function show($id)
    {
        $data = News::with('category', 'media')->findOrFail($id);
        $categories = NewsCategory::getActive()->orderBy('position', 'ASC')->get();
        return view('admin.news.edit', compact('data', 'categories'));
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
            'news_category_id' => 'required|exists:news_categories,id',
            'published_at' => 'nullable|date',
            'position' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_tablet' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_mobile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        unset($validated['image'], $validated['image_tablet'], $validated['image_mobile']);

        $news->update($validated);

        if ($request->hasFile('image')) {
            $news->clearMediaCollection('news');
            $news->addMediaFromRequest('image')->toMediaCollection('news');
        }
        if ($request->hasFile('image_tablet')) {
            $news->clearMediaCollection('news_tablet');
            $news->addMediaFromRequest('image_tablet')->toMediaCollection('news_tablet');
        }
        if ($request->hasFile('image_mobile')) {
            $news->clearMediaCollection('news_mobile');
            $news->addMediaFromRequest('image_mobile')->toMediaCollection('news_mobile');
        }

        return redirect()->route('news-admin.index')->with('success', __('app.updated successfully'));
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();
        echo json_encode(array("status" => "success"));
    }

    public function change_status($id)
    {
        $news = News::findOrFail($id);
        $news->status = !$news->status;
        $news->save();
        echo json_encode(array("status" => "success"));
    }
}
