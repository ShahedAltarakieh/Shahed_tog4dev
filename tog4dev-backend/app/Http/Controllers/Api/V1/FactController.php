<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\FactResource;
use App\Models\Fact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FactController extends Controller
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
            $perPage = $request->query('per-page', default: 50); // Default per page to 50
            $orderBy = $request->query('order', 'DESC'); // Default order to DESC
            $categoryId = $request->query('category_id');

            // Start the query
            $query = Fact::getActive()->with(['category']);

            // Apply category type filtering if `type` is provided
            if ($type) {
                $query->filterByCategoryType($type);
            }

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            // Apply ordering
            $query->orderBy('id', strtoupper($orderBy) === 'DESC' ? 'DESC' : 'ASC');

            // Paginate results dynamically based on `per_page`
            $partners = $query->paginate($perPage);

            // Return paginated data as a resource collection
            return FactResource::collection($partners);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving facts.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
