<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NewsResource;
use App\Http\Resources\Api\V1\GalleryPhotoResource;
use App\Http\Resources\Api\V1\GalleryVideoResource;
use App\Models\News;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        try {
            $keyword = $request->query('q', '');
            $limit = $request->query('limit', 6);

            if (empty($keyword)) {
                return response()->json([
                    'news' => [],
                    'photos' => [],
                    'videos' => [],
                ], 200);
            }

            $searchTerm = '%' . mb_strtolower($keyword) . '%';

            $news = News::published()
                ->with(['category', 'media'])
                ->where(function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(title_en) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(excerpt) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(excerpt_en) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(body) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(body_en) LIKE ?', [$searchTerm]);
                })
                ->orderBy('published_at', 'DESC')
                ->limit($limit)
                ->get();

            $photos = GalleryPhoto::getActive()
                ->with(['category', 'media'])
                ->where(function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(title_en) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(description) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(description_en) LIKE ?', [$searchTerm]);
                })
                ->orderBy('created_at', 'DESC')
                ->limit($limit)
                ->get();

            $videos = GalleryVideo::getActive()
                ->with(['category', 'media'])
                ->where(function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(title_en) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(description) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(description_en) LIKE ?', [$searchTerm]);
                })
                ->orderBy('created_at', 'DESC')
                ->limit($limit)
                ->get();

            return response()->json([
                'news' => NewsResource::collection($news),
                'photos' => GalleryPhotoResource::collection($photos),
                'videos' => GalleryVideoResource::collection($videos),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while searching.',
            ], 500);
        }
    }
}
