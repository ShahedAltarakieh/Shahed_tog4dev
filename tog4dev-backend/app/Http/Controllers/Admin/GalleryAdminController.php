<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class GalleryAdminController extends Controller
{
    public function indexPhotos(Request $request)
    {
        $data = GalleryPhoto::with('category')
            ->orderBy('position', 'ASC')
            ->get();

        return view('admin.gallery.photos.index', compact('data'));
    }

    public function createPhoto()
    {
        $categories = NewsCategory::getActive()->orderBy('position', 'ASC')->get();
        return view('admin.gallery.photos.create', compact('categories'));
    }

    public function storePhoto(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'title_en' => 'nullable|string',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'news_category_id' => 'required|exists:news_categories,id',
            'position' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_tablet' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_mobile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['position'] = $validated['position'] ?? 0;
        unset($validated['image'], $validated['image_tablet'], $validated['image_mobile']);

        $photo = GalleryPhoto::create($validated);

        if ($request->hasFile('image')) {
            $photo->addMediaFromRequest('image')->toMediaCollection('gallery_photos');
        }
        if ($request->hasFile('image_tablet')) {
            $photo->addMediaFromRequest('image_tablet')->toMediaCollection('gallery_photos_tablet');
        }
        if ($request->hasFile('image_mobile')) {
            $photo->addMediaFromRequest('image_mobile')->toMediaCollection('gallery_photos_mobile');
        }

        return redirect()->route('gallery-admin.photos.index')->with('success', __('app.created successfully'));
    }

    public function showPhoto($id)
    {
        $data = GalleryPhoto::with('category', 'media')->findOrFail($id);
        $categories = NewsCategory::getActive()->orderBy('position', 'ASC')->get();
        return view('admin.gallery.photos.edit', compact('data', 'categories'));
    }

    public function updatePhoto(Request $request, $id)
    {
        $photo = GalleryPhoto::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string',
            'title_en' => 'nullable|string',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'news_category_id' => 'required|exists:news_categories,id',
            'position' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_tablet' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'image_mobile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;
        unset($validated['image'], $validated['image_tablet'], $validated['image_mobile']);

        $photo->update($validated);

        if ($request->hasFile('image')) {
            $photo->clearMediaCollection('gallery_photos');
            $photo->addMediaFromRequest('image')->toMediaCollection('gallery_photos');
        }
        if ($request->hasFile('image_tablet')) {
            $photo->clearMediaCollection('gallery_photos_tablet');
            $photo->addMediaFromRequest('image_tablet')->toMediaCollection('gallery_photos_tablet');
        }
        if ($request->hasFile('image_mobile')) {
            $photo->clearMediaCollection('gallery_photos_mobile');
            $photo->addMediaFromRequest('image_mobile')->toMediaCollection('gallery_photos_mobile');
        }

        return redirect()->route('gallery-admin.photos.index')->with('success', __('app.updated successfully'));
    }

    public function destroyPhoto($id)
    {
        $photo = GalleryPhoto::findOrFail($id);
        $photo->delete();
        echo json_encode(array("status" => "success"));
    }

    public function changeStatusPhoto($id)
    {
        $photo = GalleryPhoto::findOrFail($id);
        $photo->status = !$photo->status;
        $photo->save();
        echo json_encode(array("status" => "success"));
    }

    public function indexVideos(Request $request)
    {
        $data = GalleryVideo::with('category')
            ->orderBy('position', 'ASC')
            ->get();

        return view('admin.gallery.videos.index', compact('data'));
    }

    public function createVideo()
    {
        $categories = NewsCategory::getActive()->orderBy('position', 'ASC')->get();
        return view('admin.gallery.videos.create', compact('categories'));
    }

    public function storeVideo(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'title_en' => 'nullable|string',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'video_url' => ['required', 'string', 'url', 'regex:/^https?:\/\/(www\.)?(youtube\.com|youtu\.be|vimeo\.com)\//i'],
            'thumbnail_url' => 'nullable|url',
            'news_category_id' => 'required|exists:news_categories,id',
            'position' => 'nullable|integer',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['position'] = $validated['position'] ?? 0;
        unset($validated['thumbnail']);

        $video = GalleryVideo::create($validated);

        if ($request->hasFile('thumbnail')) {
            $video->addMediaFromRequest('thumbnail')->toMediaCollection('video_thumbnails');
        }

        return redirect()->route('gallery-admin.videos.index')->with('success', __('app.created successfully'));
    }

    public function showVideo($id)
    {
        $data = GalleryVideo::with('category', 'media')->findOrFail($id);
        $categories = NewsCategory::getActive()->orderBy('position', 'ASC')->get();
        return view('admin.gallery.videos.edit', compact('data', 'categories'));
    }

    public function updateVideo(Request $request, $id)
    {
        $video = GalleryVideo::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string',
            'title_en' => 'nullable|string',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'video_url' => ['required', 'string', 'url', 'regex:/^https?:\/\/(www\.)?(youtube\.com|youtu\.be|vimeo\.com)\//i'],
            'thumbnail_url' => 'nullable|url',
            'news_category_id' => 'required|exists:news_categories,id',
            'position' => 'nullable|integer',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;
        unset($validated['thumbnail']);

        $video->update($validated);

        if ($request->hasFile('thumbnail')) {
            $video->clearMediaCollection('video_thumbnails');
            $video->addMediaFromRequest('thumbnail')->toMediaCollection('video_thumbnails');
        }

        return redirect()->route('gallery-admin.videos.index')->with('success', __('app.updated successfully'));
    }

    public function destroyVideo($id)
    {
        $video = GalleryVideo::findOrFail($id);
        $video->delete();
        echo json_encode(array("status" => "success"));
    }

    public function changeStatusVideo($id)
    {
        $video = GalleryVideo::findOrFail($id);
        $video->status = !$video->status;
        $video->save();
        echo json_encode(array("status" => "success"));
    }

    public function duplicatePhoto($id)
    {
        $original = GalleryPhoto::with('media')->findOrFail($id);

        $clone = $original->replicate();
        $clone->title = $original->title . ' (copy)';
        $clone->title_en = $original->title_en ? $original->title_en . ' (copy)' : null;
        $clone->status = 0;
        $clone->save();

        foreach (['gallery_photos', 'gallery_photos_tablet', 'gallery_photos_mobile'] as $collection) {
            $media = $original->getFirstMedia($collection);
            if ($media) {
                $clone->addMedia($media->getPath())
                    ->preservingOriginal()
                    ->toMediaCollection($collection);
            }
        }

        return redirect()->route('gallery-admin.photos.index')->with('success', __('app.duplicated successfully'));
    }

    public function duplicateVideo($id)
    {
        $original = GalleryVideo::with('media')->findOrFail($id);

        $clone = $original->replicate();
        $clone->title = $original->title . ' (copy)';
        $clone->title_en = $original->title_en ? $original->title_en . ' (copy)' : null;
        $clone->status = 0;
        $clone->save();

        $media = $original->getFirstMedia('video_thumbnails');
        if ($media) {
            $clone->addMedia($media->getPath())
                ->preservingOriginal()
                ->toMediaCollection('video_thumbnails');
        }

        return redirect()->route('gallery-admin.videos.index')->with('success', __('app.duplicated successfully'));
    }
}
