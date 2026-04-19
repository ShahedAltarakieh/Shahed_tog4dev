<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\News;
use Illuminate\Http\Request;

class AnnouncementAdminController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('order_no')->orderBy('created_at', 'desc')->get();
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $newsItems = News::published()->orderBy('created_at', 'desc')->limit(50)->get();
        return view('admin.announcements.create', compact('newsItems'));
    }

    protected function validationRules(): array
    {
        return [
            'text'           => 'required|string|max:500',
            'text_ar'        => 'required|string|max:500',
            'title'          => 'nullable|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'short_text'     => 'nullable|string|max:200',
            'short_text_ar'  => 'nullable|string|max:200',
            'link'           => 'nullable|string|max:500',
            'cta_text'       => 'nullable|string|max:100',
            'cta_text_ar'    => 'nullable|string|max:100',
            'badge_type'     => 'required|in:LIVE,INFO,ALERT,NEW',
            'target_view'    => 'required|in:desktop,mobile,both',
            'source_type'    => 'required|in:manual,news',
            'news_id'        => 'nullable|required_if:source_type,news|exists:news,id',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
        ];
    }

    protected function bilingualPayload(Request $request): array
    {
        $data = $request->only([
            'title', 'title_ar',
            'text', 'text_ar',
            'short_text', 'short_text_ar',
            'link',
            'cta_text', 'cta_text_ar',
            'badge_type', 'target_view',
            'start_date', 'end_date',
        ]);

        $data['source_type'] = $request->input('source_type', 'manual');
        $data['news_id'] = $data['source_type'] === 'news' ? $request->input('news_id') : null;
        $data['is_active'] = $request->has('is_active') ? true : false;

        return $data;
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules());

        $data = $this->bilingualPayload($request);
        $data['order_no'] = Announcement::max('order_no') + 1;

        Announcement::create($data);

        return redirect()->route('announcements.index')
            ->with('success', __('app.created successfully'));
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        $newsItems = News::published()->orderBy('created_at', 'desc')->limit(50)->get();
        return view('admin.announcements.edit', compact('announcement', 'newsItems'));
    }

    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);

        $request->validate($this->validationRules());

        $data = $this->bilingualPayload($request);
        if (!$request->filled('source_type')) {
            $data['source_type'] = $announcement->source_type ?? 'manual';
        }

        $announcement->update($data);

        return redirect()->route('announcements.index')
            ->with('success', __('app.updated successfully'));
    }

    public function destroy($id)
    {
        Announcement::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function changeStatus($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();
        return response()->json(['success' => true, 'is_active' => $announcement->is_active]);
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order', []);
        foreach ($order as $index => $id) {
            Announcement::where('id', $id)->update(['order_no' => $index]);
        }
        return response()->json(['success' => true]);
    }
}
