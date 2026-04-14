<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
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

        $announcementData = $request->validate([
            'announcement_visibility' => 'nullable|in:news_only,announcement_only,both',
            'announcement_text' => 'nullable|string|max:255',
            'announcement_cta' => 'nullable|string|max:100',
            'announcement_badge' => 'nullable|in:LIVE,INFO,ALERT,NEW',
            'announcement_start' => 'nullable|date',
            'announcement_end' => 'nullable|date|after_or_equal:announcement_start',
        ]);

        $validated = array_merge($validated, $announcementData);
        $validated['announcement_visibility'] = $validated['announcement_visibility'] ?? 'news_only';

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

        $this->syncNewsAnnouncement($news);

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

        $announcementData = $request->validate([
            'announcement_visibility' => 'nullable|in:news_only,announcement_only,both',
            'announcement_text' => 'nullable|string|max:255',
            'announcement_cta' => 'nullable|string|max:100',
            'announcement_badge' => 'nullable|in:LIVE,INFO,ALERT,NEW',
            'announcement_start' => 'nullable|date',
            'announcement_end' => 'nullable|date|after_or_equal:announcement_start',
        ]);

        $validated = array_merge($validated, $announcementData);
        $validated['announcement_visibility'] = $validated['announcement_visibility'] ?? 'news_only';

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

        $this->syncNewsAnnouncement($news);

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

    public function duplicate($id)
    {
        $original = News::with('media')->findOrFail($id);

        $clone = $original->replicate();
        $clone->title = $original->title . ' (copy)';
        $clone->title_en = $original->title_en ? $original->title_en . ' (copy)' : null;
        $clone->status = 0;
        $clone->save();

        foreach (['news', 'news_tablet', 'news_mobile'] as $collection) {
            $media = $original->getFirstMedia($collection);
            if ($media) {
                $clone->addMedia($media->getPath())
                    ->preservingOriginal()
                    ->toMediaCollection($collection);
            }
        }

        return redirect()->route('news-admin.index')->with('success', __('app.duplicated successfully'));
    }

    private function syncNewsAnnouncement(News $news)
    {
        $visibility = $news->announcement_visibility ?? 'news_only';

        if ($visibility === 'news_only') {
            Announcement::where('news_id', $news->id)->where('source_type', 'news')->delete();
            return;
        }

        $announcementText = $news->announcement_text ?: $news->getLocalizationTitle();

        $slug = $news->slug ?? $news->id;
        $newsLink = '/en/news/' . $slug;

        $badgeType = in_array($news->announcement_badge, ['LIVE', 'INFO', 'ALERT', 'NEW'])
            ? $news->announcement_badge
            : 'NEW';

        Announcement::updateOrCreate(
            ['news_id' => $news->id, 'source_type' => 'news'],
            [
                'title' => $news->getLocalizationTitle(),
                'text' => $announcementText,
                'badge_type' => $badgeType,
                'cta_text' => $news->announcement_cta,
                'link' => $newsLink,
                'target_view' => 'both',
                'is_active' => (bool) $news->status,
                'start_date' => $news->announcement_start,
                'end_date' => $news->announcement_end,
                'order_no' => Announcement::max('order_no') + 1,
            ]
        );
    }
}
