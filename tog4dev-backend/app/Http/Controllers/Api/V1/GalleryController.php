<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\GalleryPhotoResource;
use App\Http\Resources\Api\V1\GalleryVideoResource;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function photos(Request $request)
    {
        try {
            $perPage = $request->query('per-page', 12);
            $categorySlug = $request->query('category');
            $search = $request->query('search');

            $query = GalleryPhoto::getActive()
                ->with(['category', 'media'])
                ->orderBy('position', 'ASC')
                ->orderBy('created_at', 'DESC');

            if ($categorySlug) {
                $locale = app()->getLocale();
                $column = $locale === 'ar' ? 'slug' : 'slug_en';
                $query->whereHas('category', function ($q) use ($categorySlug, $column) {
                    $q->where($column, $categorySlug);
                });
            }

            if ($search) {
                $searchTerm = '%' . mb_strtolower($search) . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(title_en) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(description) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(description_en) LIKE ?', [$searchTerm]);
                });
            }

            $photos = $query->paginate($perPage);

            return GalleryPhotoResource::collection($photos);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving photos.',
            ], 500);
        }
    }

    public function videos(Request $request)
    {
        try {
            $perPage = $request->query('per-page', 12);
            $categorySlug = $request->query('category');
            $search = $request->query('search');
            $displayTarget = $request->query('display_target');

            $query = GalleryVideo::getActive()
                ->with(['category', 'media'])
                ->orderBy('position', 'ASC')
                ->orderBy('created_at', 'DESC');

            if ($displayTarget && in_array($displayTarget, ['mobile', 'desktop'])) {
                $query->where(function ($q) use ($displayTarget) {
                    $q->where('display_target', $displayTarget)
                      ->orWhere('display_target', 'both');
                });
            }

            if ($categorySlug) {
                $locale = app()->getLocale();
                $column = $locale === 'ar' ? 'slug' : 'slug_en';
                $query->whereHas('category', function ($q) use ($categorySlug, $column) {
                    $q->where($column, $categorySlug);
                });
            }

            if ($search) {
                $searchTerm = '%' . mb_strtolower($search) . '%';
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(title_en) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(description) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(description_en) LIKE ?', [$searchTerm]);
                });
            }

            $videos = $query->paginate($perPage);

            return GalleryVideoResource::collection($videos);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving videos.',
            ], 500);
        }
    }
}
