<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use Illuminate\Http\Request;

class GalleryAdminController extends Controller
{
    public function indexPhotos(Request $request)
    {
        $perPage = $request->query('per-page', 20);
        $photos = GalleryPhoto::with('category')
            ->orderBy('position', 'ASC')
            ->paginate($perPage);

        return response()->json(['data' => $photos]);
    }

    public function storePhoto(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string',
            'title_en' => 'nullable|string',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'news_category_id' => 'nullable|exists:news_categories,id',
            'status' => 'boolean',
            'position' => 'integer',
        ]);

        $photo = GalleryPhoto::create($validated);

        if ($request->hasFile('image')) {
            $photo->addMediaFromRequest('image')->toMediaCollection('gallery_photos');
        }

        return response()->json(['data' => $photo, 'message' => 'Photo created successfully.'], 201);
    }

    public function showPhoto($id)
    {
        $photo = GalleryPhoto::with('category', 'media')->findOrFail($id);
        return response()->json(['data' => $photo]);
    }

    public function updatePhoto(Request $request, $id)
    {
        $photo = GalleryPhoto::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string',
            'title_en' => 'nullable|string',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'news_category_id' => 'nullable|exists:news_categories,id',
            'status' => 'boolean',
            'position' => 'integer',
        ]);

        $photo->update($validated);

        if ($request->hasFile('image')) {
            $photo->clearMediaCollection('gallery_photos');
            $photo->addMediaFromRequest('image')->toMediaCollection('gallery_photos');
        }

        return response()->json(['data' => $photo, 'message' => 'Photo updated successfully.']);
    }

    public function destroyPhoto($id)
    {
        $photo = GalleryPhoto::findOrFail($id);
        $photo->delete();
        return response()->json(['message' => 'Photo deleted successfully.']);
    }

    public function indexVideos(Request $request)
    {
        $perPage = $request->query('per-page', 20);
        $videos = GalleryVideo::with('category')
            ->orderBy('position', 'ASC')
            ->paginate($perPage);

        return response()->json(['data' => $videos]);
    }

    public function storeVideo(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string',
            'title_en' => 'nullable|string',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'video_url' => 'nullable|string',
            'thumbnail_url' => 'nullable|string',
            'news_category_id' => 'nullable|exists:news_categories,id',
            'status' => 'boolean',
            'position' => 'integer',
        ]);

        $video = GalleryVideo::create($validated);

        if ($request->hasFile('thumbnail')) {
            $video->addMediaFromRequest('thumbnail')->toMediaCollection('video_thumbnails');
        }

        return response()->json(['data' => $video, 'message' => 'Video created successfully.'], 201);
    }

    public function showVideo($id)
    {
        $video = GalleryVideo::with('category', 'media')->findOrFail($id);
        return response()->json(['data' => $video]);
    }

    public function updateVideo(Request $request, $id)
    {
        $video = GalleryVideo::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string',
            'title_en' => 'nullable|string',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'video_url' => 'nullable|string',
            'thumbnail_url' => 'nullable|string',
            'news_category_id' => 'nullable|exists:news_categories,id',
            'status' => 'boolean',
            'position' => 'integer',
        ]);

        $video->update($validated);

        if ($request->hasFile('thumbnail')) {
            $video->clearMediaCollection('video_thumbnails');
            $video->addMediaFromRequest('thumbnail')->toMediaCollection('video_thumbnails');
        }

        return response()->json(['data' => $video, 'message' => 'Video updated successfully.']);
    }

    public function destroyVideo($id)
    {
        $video = GalleryVideo::findOrFail($id);
        $video->delete();
        return response()->json(['message' => 'Video deleted successfully.']);
    }
}
