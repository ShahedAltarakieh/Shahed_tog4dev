<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Partner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\PartnerResource;

class PartnerController extends Controller
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
            // Get the optional category type from the request
            $type = $request->query('type');
            $perPage = $request->query('per-page', 50); // Default per page to 50
            $orderBy = $request->query('order', 'DESC'); // Default order to DESC
            $home_only = $request->query('home_only');

            // Start the query
            $query = Partner::getActive()->with(['category', 'media']);

            // Apply category type filtering if `type` is provided
            if ($type) {
                $query->filterByCategoryType($type);
            }

            if ($home_only) {
                $query->where('show_in_home', 1);
            }

            // Apply ordering
            $query->orderBy('id', strtoupper($orderBy) === 'DESC' ? 'DESC' : 'ASC');

            // Paginate results dynamically based on `per_page`
            $partners = $query->paginate($perPage);

            // Return paginated data as a resource collection
            return PartnerResource::collection($partners);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving partners.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
