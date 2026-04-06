<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
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

            // Mapping of types to database values
            $typeMapping = [
                'organization' => 1,
                'projects' => 2,
                'crowdfunding' => 3,
                'ramadan' => -2
            ];

            // Start the query
            $query = Category::getActive()->with(['media']);

            // Apply filtering by type if provided and valid
            if ($type && isset($typeMapping[$type])) {
                if($typeMapping[$type] == -2){
                    $query->where('id', 24);
                } else {
                    $query->where('type', $typeMapping[$type]);
                }
            }

            // Apply ordering
            $query->orderBy('all_option', 'DESC')
                ->orderBy('id', strtoupper($orderBy) === 'DESC' ? 'DESC' : 'ASC');

            // Paginate results dynamically based on `per_page`
            $partners = $query->paginate($perPage);

            // Return paginated data as a resource collection
            return CategoryResource::collection($partners);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving categories.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
