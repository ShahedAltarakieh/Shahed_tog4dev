<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\QuickContributionResource;
use App\Models\QuickContribution;
use Illuminate\Http\Request;

class QuickContributionController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve query parameters
        $typeId = $request->query('type_id');
        $categoryId = $request->query('category_id');
        $orderBy = $request->query('order', 'DESC'); // Default order to DESC

        // If category_id is not provided and type_id is not 1
        if (!$categoryId && $typeId != 1) {
            $fallbackCategory = \App\Models\Category::where('all_option', 1)->first();

            // Set category_id to the fallback category's id if it exists
            if ($fallbackCategory) {
                $categoryId = $fallbackCategory->id;
            }
        }

        // Build the query
        $query = QuickContribution::getActive()->with('prices');

        if ($typeId && ($categoryId == '' || $categoryId)) {
            $query->where('type_id', $typeId);

            if($typeId != 1){
                $query->where('category_id', $categoryId);
            }

            // Apply ordering
            $query->orderBy('id', strtoupper($orderBy) === 'DESC' ? 'DESC' : 'ASC');

            // Execute the query and get the results
            $quickContributions = $query->get();
            return QuickContributionResource::collection($quickContributions);
        } else {
            return response()->json([
                'success' => false,
                'data' => null
            ], 422);
        }


    }

}
