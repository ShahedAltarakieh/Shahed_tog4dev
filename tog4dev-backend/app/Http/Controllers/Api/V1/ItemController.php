<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\Api\V1\ItemResource;
use App\Http\Resources\Api\V1\StoryResource;
use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
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
            $type = $request->query('type');
            $perPage = $request->query('per-page', 100);
            $orderBy = strtoupper($request->query('order', 'ASC')) === 'DESC' ? 'DESC' : 'ASC';
            $categoryId = $request->query('category_id');
            $home_only = $request->query('home_only');


            // Map home_only → home
            if ($type === "home_only") {
                $orderingType = "home";
            } else {
                $orderingType = $type;
            }

            $type_int = (new \App\Helpers\Helper)->getTypes($orderingType);
            if($orderingType == "ramadan"){
                $type_int = 4;
            }
            $query = Item::getActive()
                ->with(['category', 'media', 'additionalInfo'])
                ->select('items.*', 'ordering_item.sort_order')
                ->leftJoin('ordering_item', function ($join) use ($type_int) {
                    $join->on('ordering_item.item_id', '=', 'items.id');

                    // Filter ordering_item by type inside the join
                    if ($type_int !== null) {
                        $join->where('ordering_item.type', $type_int);
                    }
                });


            if ($type === "home_only") {
                $query->where('show_in_home', 1)->where('status', 1);
            } elseif ($type) {
                $query->filterByCategoryType($type);
            }

            // Apply category filter
            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }
            if ($type === "ramadan") {
                $query->where('category_id', 23)->orWhere('category_id', 24)->where('status', 1);
            }
            // Apply ordering:
            // 1. Order by sort_order from pivot table if exists
            // 2. Fallback to item.id
            $query->orderByRaw('
                CASE WHEN ordering_item.sort_order IS NULL THEN 1 ELSE 0 END, 
                ordering_item.sort_order ' . $orderBy . ',
                items.id ' . $orderBy
            );

            // Paginate results dynamically
            $stories = $query->paginate($perPage);

            return ItemResource::collection($stories);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving stories.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function show($slug)
    {
        try {
            // Fetch the item with its relationships
            if(app()->getLocale() == "ar"){
                $item = Item::with(['category', 'media', 'additionalInfo'])->where("slug", $slug)->first();
            } else {
                $item = Item::with(['category', 'media', 'additionalInfo'])->where("slug_en", $slug)->first();
            }

            // Return the item as a resource
            if($item && $item->status == true){
                return new ItemResource($item);
            }
            else {
                return response()->json([
                    'message' => 'Item not found.',
                    "redirect" => true
                ], 404);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Item not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving the item.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
