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

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:500',
            'title' => 'nullable|string|max:255',
            'short_text' => 'nullable|string|max:200',
            'link' => 'nullable|string|max:500',
            'cta_text' => 'nullable|string|max:100',
            'badge_type' => 'required|in:LIVE,INFO,ALERT,NEW',
            'target_view' => 'required|in:desktop,mobile,both',
            'source_type' => 'required|in:manual,news',
            'news_id' => 'nullable|required_if:source_type,news|exists:news,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data = $request->only([
            'title', 'text', 'short_text', 'link', 'cta_text',
            'badge_type', 'target_view', 'start_date', 'end_date',
        ]);

        $data['source_type'] = $request->input('source_type', 'manual');
        $data['news_id'] = $data['source_type'] === 'news' ? $request->input('news_id') : null;
        $data['is_active'] = $request->has('is_active') ? true : false;
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

        $request->validate([
            'text' => 'required|string|max:500',
            'title' => 'nullable|string|max:255',
            'short_text' => 'nullable|string|max:200',
            'link' => 'nullable|string|max:500',
            'cta_text' => 'nullable|string|max:100',
            'badge_type' => 'required|in:LIVE,INFO,ALERT,NEW',
            'target_view' => 'required|in:desktop,mobile,both',
            'source_type' => 'required|in:manual,news',
            'news_id' => 'nullable|required_if:source_type,news|exists:news,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $data = $request->only([
            'title', 'text', 'short_text', 'link', 'cta_text',
            'badge_type', 'target_view', 'start_date', 'end_date',
        ]);

        $data['source_type'] = $request->input('source_type', $announcement->source_type ?? 'manual');
        $data['news_id'] = $data['source_type'] === 'news' ? $request->input('news_id') : null;
        $data['is_active'] = $request->has('is_active') ? true : false;

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
