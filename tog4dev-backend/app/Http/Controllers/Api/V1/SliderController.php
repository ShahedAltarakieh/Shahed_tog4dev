<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\SliderResource;
use App\Http\Resources\Api\V1\StoryResource;
use App\Http\Controllers\Controller;
use App\Models\Sliders;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of partners with optional filtering by category type.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per-page', 50); // Default per page to 50
            $orderBy = $request->query('order', 'DESC'); // Default order to DESC

            // Start the query
            $query = Sliders::getActive()->with(['media']);

            // Apply ordering
            $query->orderBy('id', strtoupper($orderBy) === 'DESC' ? 'DESC' : 'ASC');

            // Paginate results dynamically based on `per_page`
            $stories = $query->paginate($perPage);

            // Return paginated data as a resource collection
            return SliderResource::collection($stories);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving sliders.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
