<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Testimonial;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\TestimonialResource;

class TestimonialController extends Controller
{
    /**
     * Display a listing of testimonials with optional filtering by category type.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            // Get the optional category type from the request
            $type = $request->query('type');
            $perPage = $request->query('per-page', 50); // Default per page to 50
            $orderBy = $request->query('order', 'DESC'); // Default order to DESC
            $categoryId = $request->query('category_id');
            $home_only = $request->query('home_only');

            // Start the query
            $query = Testimonial::getActive()->with(['category', 'media']);

            // Apply category type filtering if `type` is provided
            if ($type) {
                $query->filterByCategoryType($type);
            }

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            if ($home_only) {
                $query->where('show_in_home', 1);
            }

            // Apply ordering
            $query->orderBy('id', strtoupper($orderBy) === 'DESC' ? 'DESC' : 'ASC');

            // Paginate results dynamically based on `per_page`
            $testimonials = $query->paginate($perPage);

            // Return paginated data as a resource collection
            return TestimonialResource::collection($testimonials);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving testimonials.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
