<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NewsCategoryResource;
use App\Http\Resources\Api\V1\NewsResource;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per-page', 9);
            $categorySlug = $request->query('category');
            $search = $request->query('search');

            $query = News::published()
                ->with(['category', 'media'])
                ->orderBy('published_at', 'DESC');

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
                      ->orWhereRaw('LOWER(excerpt) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(excerpt_en) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(body) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(body_en) LIKE ?', [$searchTerm]);
                });
            }

            $news = $query->paginate($perPage);

            return NewsResource::collection($news);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving news.',
            ], 500);
        }
    }

    public function show($slug)
    {
        try {
            $locale = app()->getLocale();
            $column = $locale === 'ar' ? 'slug' : 'slug_en';

            $news = News::published()
                ->with(['category', 'media'])
                ->where($column, $slug)
                ->first();

            if (!$news) {
                $fallbackColumn = $locale === 'ar' ? 'slug_en' : 'slug';
                $news = News::published()
                    ->with(['category', 'media'])
                    ->where($fallbackColumn, $slug)
                    ->first();
            }

            if (!$news) {
                return response()->json([
                    'message' => 'News article not found.',
                    'redirect' => true,
                ], 404);
            }

            return new NewsResource($news);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the news article.',
            ], 500);
        }
    }

    public function related($slug, Request $request)
    {
        try {
            $locale = app()->getLocale();
            $column = $locale === 'ar' ? 'slug' : 'slug_en';
            $limit = $request->query('limit', 4);

            $news = News::published()->where($column, $slug)->first();

            if (!$news) {
                return response()->json(['data' => []], 200);
            }

            $related = News::published()
                ->with(['category', 'media'])
                ->where('id', '!=', $news->id)
                ->when($news->news_category_id, function ($q) use ($news) {
                    $q->where('news_category_id', $news->news_category_id);
                })
                ->orderBy('published_at', 'DESC')
                ->limit($limit)
                ->get();

            if ($related->count() < $limit) {
                $existingIds = $related->pluck('id')->push($news->id)->toArray();
                $fallback = News::published()
                    ->with(['category', 'media'])
                    ->whereNotIn('id', $existingIds)
                    ->orderBy('published_at', 'DESC')
                    ->limit($limit - $related->count())
                    ->get();
                $related = $related->merge($fallback);
            }

            return NewsResource::collection($related);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving related news.',
            ], 500);
        }
    }

    public function categories()
    {
        try {
            $categories = NewsCategory::getActive()
                ->orderBy('position', 'ASC')
                ->get();

            return NewsCategoryResource::collection($categories);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving categories.',
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $search = $request->query('q', '');
            $perPage = $request->query('per-page', 12);

            if (empty($search)) {
                return response()->json(['data' => [], 'meta' => ['total' => 0]], 200);
            }

            $searchTerm = '%' . mb_strtolower($search) . '%';
            $results = News::published()
                ->with(['category', 'media'])
                ->where(function ($q) use ($searchTerm) {
                    $q->whereRaw('LOWER(title) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(title_en) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(excerpt) LIKE ?', [$searchTerm])
                      ->orWhereRaw('LOWER(excerpt_en) LIKE ?', [$searchTerm]);
                })
                ->orderBy('published_at', 'DESC')
                ->paginate($perPage);

            return NewsResource::collection($results);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while searching.',
            ], 500);
        }
    }
}
