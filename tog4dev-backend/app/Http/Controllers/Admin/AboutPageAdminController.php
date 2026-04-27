<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutPage;
use App\Models\AboutSection;
use App\Models\AboutSectionItem;
use App\Models\AboutPageVersion;
use Illuminate\Http\Request;

class AboutPageAdminController extends Controller
{
    protected $sectionKeys = [
        'hero', 'intro', 'highlights', 'statement', 'visionMission',
        'coreValues', 'founders', 'beliefs', 'stats', 'slogan', 'contact', 'partners'
    ];

    public function index()
    {
        $pages = AboutPage::withCount('sections')->orderByDesc('updated_at')->get();
        return view('admin.about.index', compact('pages'));
    }

    public function create()
    {
        $sectionKeys = $this->sectionKeys;
        return view('admin.about.create', compact('sectionKeys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_code' => 'required|string|max:5',
            'meta_title' => 'nullable|string|max:255',
            'meta_title_en' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_description_en' => 'nullable|string',
        ]);

        $page = AboutPage::create([
            'country_code' => $request->country_code,
            'status' => 'draft',
            'version' => 1,
            'meta_title' => $request->meta_title,
            'meta_title_en' => $request->meta_title_en,
            'meta_description' => $request->meta_description,
            'meta_description_en' => $request->meta_description_en,
        ]);

        foreach ($this->sectionKeys as $index => $key) {
            $page->sections()->create([
                'section_key' => $key,
                'sort_order' => $index,
                'is_visible' => true,
            ]);
        }

        return redirect()->route('about-admin.edit', $page->id)
            ->with('success', __('app.created successfully'));
    }

    public function edit($id)
    {
        $page = AboutPage::with(['sections' => function ($q) {
            $q->orderBy('sort_order');
            $q->with(['items' => function ($q2) {
                $q2->orderBy('sort_order');
            }]);
        }])->findOrFail($id);

        $sectionKeys = $this->sectionKeys;
        $versions = $page->versions()->limit(20)->get();

        return view('admin.about.edit', compact('page', 'sectionKeys', 'versions'));
    }

    public function update(Request $request, $id)
    {
        $page = AboutPage::findOrFail($id);

        $page->update([
            'country_code' => $request->country_code ?? $page->country_code,
            'meta_title' => $request->meta_title,
            'meta_title_en' => $request->meta_title_en,
            'meta_description' => $request->meta_description,
            'meta_description_en' => $request->meta_description_en,
        ]);

        return redirect()->route('about-admin.edit', $page->id)
            ->with('success', __('app.updated successfully'));
    }

    public function updateSection(Request $request, $pageId, $sectionId)
    {
        $section = AboutSection::where('about_page_id', $pageId)->findOrFail($sectionId);

        if ($section->section_key === 'hero') {
            // Hero stores title + subtitle (AR/EN). Page name "About Us" is fixed in the frontend.
            $section->update([
                'title' => $request->input('title'),
                'title_en' => $request->input('title_en'),
                'subtitle' => $request->input('subtitle'),
                'subtitle_en' => $request->input('subtitle_en'),
            ]);
        } else {
            $section->update($request->only([
                'title', 'title_en', 'subtitle', 'subtitle_en',
                'body', 'body_en', 'video_url',
                'cta_text', 'cta_text_en', 'cta_link', 'cta_link_en',
                'layout', 'is_visible',
            ]));

            if ($request->has('settings')) {
                $section->update(['settings' => json_decode($request->settings, true)]);
            }

            if ($request->hasFile('section_image')) {
                $request->validate(['section_image' => 'image|mimes:jpg,jpeg,png,webp|max:2048']);
                $path = $request->file('section_image')->store('about/sections', 'public');
                $section->update(['image' => '/storage/' . $path]);
            }
        }

        return response()->json(['success' => true, 'message' => __('app.updated successfully')]);
    }

    public function storeItem(Request $request, $pageId, $sectionId)
    {
        $section = AboutSection::where('about_page_id', $pageId)->findOrFail($sectionId);

        $maxOrder = $section->items()->max('sort_order') ?? -1;

        $item = $section->items()->create([
            'title' => $request->title,
            'title_en' => $request->title_en,
            'description' => $request->description,
            'description_en' => $request->description_en,
            'icon' => $request->icon,
            'link' => $request->link,
            'link_en' => $request->link_en,
            'value' => $request->value,
            'label' => $request->label,
            'label_en' => $request->label_en,
            'social_links' => $request->social_links ? json_decode($request->social_links, true) : null,
            'extra' => $request->extra ? json_decode($request->extra, true) : null,
            'sort_order' => $maxOrder + 1,
            'is_visible' => true,
        ]);

        if ($request->hasFile('item_image')) {
            $request->validate(['item_image' => 'image|mimes:jpg,jpeg,png,webp|max:2048']);
            $path = $request->file('item_image')->store('about/items', 'public');
            $item->update(['image' => '/storage/' . $path]);
        }

        return response()->json(['success' => true, 'item' => $item, 'message' => __('app.created successfully')]);
    }

    public function updateItem(Request $request, $pageId, $sectionId, $itemId)
    {
        $item = AboutSectionItem::whereHas('section', function ($q) use ($pageId) {
            $q->where('about_page_id', $pageId);
        })->where('about_section_id', $sectionId)->findOrFail($itemId);

        $item->update($request->only([
            'title', 'title_en', 'description', 'description_en',
            'icon', 'link', 'link_en', 'value', 'label', 'label_en', 'is_visible',
        ]));

        if ($request->has('social_links')) {
            $item->update(['social_links' => json_decode($request->social_links, true)]);
        }

        if ($request->has('extra')) {
            $item->update(['extra' => json_decode($request->extra, true)]);
        }

        if ($request->hasFile('item_image')) {
            $request->validate(['item_image' => 'image|mimes:jpg,jpeg,png,webp|max:2048']);
            $path = $request->file('item_image')->store('about/items', 'public');
            $item->update(['image' => '/storage/' . $path]);
        }

        return response()->json(['success' => true, 'message' => __('app.updated successfully')]);
    }

    public function deleteItem($pageId, $sectionId, $itemId)
    {
        $item = AboutSectionItem::whereHas('section', function ($q) use ($pageId) {
            $q->where('about_page_id', $pageId);
        })->where('about_section_id', $sectionId)->findOrFail($itemId);

        $item->delete();

        return response()->json(['success' => true, 'message' => __('app.deleted successfully')]);
    }

    public function reorderSections(Request $request, $pageId)
    {
        $request->validate(['order' => 'required|array']);

        foreach ($request->order as $index => $sectionId) {
            AboutSection::where('about_page_id', $pageId)
                ->where('id', $sectionId)
                ->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function reorderItems(Request $request, $pageId, $sectionId)
    {
        $request->validate(['order' => 'required|array']);

        foreach ($request->order as $index => $itemId) {
            AboutSectionItem::where('about_section_id', $sectionId)
                ->where('id', $itemId)
                ->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function toggleVisibility($pageId, $sectionId)
    {
        $section = AboutSection::where('about_page_id', $pageId)->findOrFail($sectionId);
        $section->update(['is_visible' => !$section->is_visible]);

        return response()->json(['success' => true, 'is_visible' => $section->is_visible]);
    }

    public function publish($id)
    {
        $page = AboutPage::with('sections.items')->findOrFail($id);

        $snapshot = $page->toArray();
        $snapshot['sections'] = $page->sections->map(function ($s) {
            $data = $s->toArray();
            $data['items'] = $s->items->toArray();
            return $data;
        })->toArray();

        AboutPageVersion::create([
            'about_page_id' => $page->id,
            'version' => $page->version,
            'snapshot' => $snapshot,
            'action' => 'publish',
        ]);

        $page->update([
            'status' => 'published',
            'version' => $page->version + 1,
            'published_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => __('app.published successfully'),
            'status' => 'published',
            'version' => $page->version,
        ]);
    }

    public function unpublish($id)
    {
        $page = AboutPage::findOrFail($id);
        $page->update(['status' => 'draft']);

        return response()->json([
            'success' => true,
            'message' => __('app.unpublished_successfully'),
            'status' => 'draft',
            'version' => $page->version,
        ]);
    }

    public function rollback($id, $versionId)
    {
        $page = AboutPage::findOrFail($id);
        $version = AboutPageVersion::where('about_page_id', $id)->findOrFail($versionId);

        $snapshot = $version->snapshot;

        $page->sections()->delete();

        if (isset($snapshot['sections'])) {
            foreach ($snapshot['sections'] as $sData) {
                $section = $page->sections()->create([
                    'section_key' => $sData['section_key'],
                    'title' => $sData['title'] ?? null,
                    'title_en' => $sData['title_en'] ?? null,
                    'subtitle' => $sData['subtitle'] ?? null,
                    'subtitle_en' => $sData['subtitle_en'] ?? null,
                    'body' => $sData['body'] ?? null,
                    'body_en' => $sData['body_en'] ?? null,
                    'image' => $sData['image'] ?? null,
                    'video_url' => $sData['video_url'] ?? null,
                    'cta_text' => $sData['cta_text'] ?? null,
                    'cta_text_en' => $sData['cta_text_en'] ?? null,
                    'cta_link' => $sData['cta_link'] ?? null,
                    'cta_link_en' => $sData['cta_link_en'] ?? null,
                    'layout' => $sData['layout'] ?? null,
                    'settings' => $sData['settings'] ?? null,
                    'sort_order' => $sData['sort_order'] ?? 0,
                    'is_visible' => $sData['is_visible'] ?? true,
                ]);

                if (isset($sData['items'])) {
                    foreach ($sData['items'] as $iData) {
                        $section->items()->create([
                            'title' => $iData['title'] ?? null,
                            'title_en' => $iData['title_en'] ?? null,
                            'description' => $iData['description'] ?? null,
                            'description_en' => $iData['description_en'] ?? null,
                            'image' => $iData['image'] ?? null,
                            'icon' => $iData['icon'] ?? null,
                            'link' => $iData['link'] ?? null,
                            'link_en' => $iData['link_en'] ?? null,
                            'value' => $iData['value'] ?? null,
                            'label' => $iData['label'] ?? null,
                            'label_en' => $iData['label_en'] ?? null,
                            'social_links' => $iData['social_links'] ?? null,
                            'extra' => $iData['extra'] ?? null,
                            'sort_order' => $iData['sort_order'] ?? 0,
                            'is_visible' => $iData['is_visible'] ?? true,
                        ]);
                    }
                }
            }
        }

        $page->update(['status' => 'draft']);

        return response()->json(['success' => true, 'message' => 'Rolled back to version ' . $version->version]);
    }

    public function destroy($id)
    {
        $page = AboutPage::findOrFail($id);
        $page->delete();

        return response()->json(['success' => true, 'message' => __('app.deleted successfully')]);
    }
}
