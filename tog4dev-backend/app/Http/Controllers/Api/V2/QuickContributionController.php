<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V2\QuickContributionResource;
use App\Models\QuickContribution;
use App\Models\QuickContributionPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Support\Facades\Log;

class QuickContributionController extends Controller
{
    /**
     * Get all Quick Contributions.
     */
    public function index(Request $request)
    {
        try {
            $typeId = $request->query('type_id');
            $perPage = $request->query('per-page', 10);
            $order = $request->query('order', 'DESC');
    
            $query = QuickContribution::with('category');
    
            if ($typeId) {
                $query->where('type_id', $typeId);
            }
    
            $query->orderBy('id', strtoupper($order) === 'DESC' ? 'DESC' : 'ASC');
    
            $quickContributions = $query->paginate($perPage);
    
            return response()->json([
                'success' => true,
                'data' => $quickContributions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching quick contributions.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Get a single Quick Contribution.
     */
    public function show($id)
    {
        try {
            $contribution = QuickContribution::with('prices')->find($id);
            if (!$contribution) {
                return response()->json([
                    'message' => 'Quick Contribution not found.',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'message' => 'Success',
                'data' => new QuickContributionResource($contribution)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching contribution.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new Quick Contribution.
     */
    public function store(Request $request)
    {
        try {
            $this->logRequest($request->all());
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'description' => 'required|string',
                'description_en' => 'required|string',
                'location' => 'required|string|max:255',
                'location_en' => 'required|string|max:255',
                'type_id' => 'required|integer',
                'category_id' => 'nullable|integer',
                'target' => 'nullable|integer',
                'target_usd' => 'nullable|integer',
                'status' => 'nullable|boolean',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'prices' => 'nullable|array',
                'prices.*.price' => 'nullable|numeric',
                'prices.*.price_usd' => 'nullable|numeric',
                'contributionId' => 'nullable|integer',
                'x-source-type' => 'nullable|string',
                // 'image' => 'nullable|string',
                // 'image_ar' => 'nullable|string',
            ]);

            $validatedData["source"] = $validatedData["x-source-type"] ?? null;
            $validatedData["need_sync"] = $validatedData["x-source-type"] == "odoo" ? 0 : 1;

            if($validatedData["contributionId"]){
                $validatedData["odoo_id"] = $validatedData["contributionId"];
            } else {
                $validatedData["odoo_id"] = null;
            }

            $quickContribution = QuickContribution::create([
                'title' => $validatedData['title'],
                'title_en' => $validatedData['title_en'],
                'description' => $validatedData['description'],
                'description_en' => $validatedData['description_en'],
                'location' => $validatedData['location'],
                'location_en' => $validatedData['location_en'],
                'target' => $validatedData['target'],
                'target_usd' => $validatedData['target_usd'],
                'type_id' => $validatedData['type_id'],
                'category_id' => $validatedData['category_id'],
                'status' => $request->has('status') ? 1 : 0,
                "source" => $validatedData["source"],
                "need_sync" => $validatedData["need_sync"],
                "odoo_id" => $validatedData["odoo_id"]
            ]);

            // if($validatedData["image_ar"]){
            //     try {
            //         $quickContribution->addMediaFromUrl($validatedData["image_ar"])->toMediaCollection('quick_contribute');
            //     } catch (\Exception $e) {
            //         // Log error but don't break main request
            //         \Log::error('Image upload failed: '.$e->getMessage());
            //     }
            // }
            // if($validatedData["image"]){
            //     try {
            //         $quickContribution->addMediaFromUrl($validatedData["image"])->toMediaCollection('quick_contribute_en');
            //     } catch (\Exception $e) {
            //         // Log error but don't break main request
            //         \Log::error('Image upload failed: '.$e->getMessage());
            //     }
            // }

            if (!empty($validatedData['prices'])) {
                foreach ($validatedData['prices'] as $priceData) {
                    QuickContributionPrice::create([
                        'quick_contribution_id' => $quickContribution->id,
                        'price' => $priceData['price'] ?? null,
                        'price_usd' => $priceData['price_usd'] ?? null,
                    ]);
                }
            }
            return response()->json([
                'success' => true,
                'message' => __('app.add successfully'),
                'data' => new QuickContributionResource($quickContribution)
            ], 201);
        } catch (ValidationException $e) {
            $this->logError($request->all(), $e);
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            $this->logError($request->all(), $e);
            return response()->json([
                'message' => 'Error creating user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update an existing Quick Contribution.
     */
    public function update(Request $request, $id)
    {
        try{
            $this->logRequest($request->all());
            $quickContribution = QuickContribution::findOrFail($id);

            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'description' => 'required|string',
                'description_en' => 'required|string',
                'location' => 'required|string|max:255',
                'location_en' => 'required|string|max:255',
                'type_id' => 'required|integer',
                'category_id' => 'nullable|integer',
                'target' => 'nullable|integer',
                'target_usd' => 'nullable|integer',
                'status' => 'nullable|boolean',
                'prices' => 'nullable|array',
                'prices.*.id' => 'nullable|integer|exists:quick_contribution_prices,id',
                'prices.*.price' => 'nullable|numeric',
                'prices.*.price_usd' => 'nullable|numeric',
                // 'image' => 'nullable|string',
                // 'image_ar' => 'nullable|string',
            ]);

            $quickContribution->update([
                'title' => $validatedData['title'],
                'title_en' => $validatedData['title_en'],
                'description' => $validatedData['description'],
                'description_en' => $validatedData['description_en'],
                'location' => $validatedData['location'],
                'location_en' => $validatedData['location_en'],
                'target' => $validatedData['target'],
                'target_usd' => $validatedData['target_usd'],
                'type_id' => $validatedData['type_id'],
                'category_id' => $validatedData['category_id'],
                'status' => $request->has('status') ? 1 : 0,
            ]);

            // if($validatedData["image"]){
            //     try {
            //         $quickContribution->clearMediaCollection('quick_contribute_en');
            //         $quickContribution->addMediaFromUrl($validatedData["image"])->toMediaCollection('quick_contribute_en');
            //     } catch (\Exception $e) {
            //         // Log error but don't break main request
            //         \Log::error('Image upload failed: '.$e->getMessage());
            //     }
            // }
            // if($validatedData["image_ar"]){
            //     try {
            //         $quickContribution->clearMediaCollection('quick_contribute');
            //         $quickContribution->addMediaFromUrl($validatedData["image_ar"])->toMediaCollection('quick_contribute');
            //     } catch (\Exception $e) {
            //         // Log error but don't break main request
            //         \Log::error('Image upload failed: '.$e->getMessage());
            //     }
            // }

            if (!empty($validatedData['prices'])) {
                foreach ($validatedData['prices'] as $priceData) {
                    if (isset($priceData['id'])) {
                        $price = QuickContributionPrice::find($priceData['id']);
                        if ($price) {
                            $price->update([
                                'price' => $priceData['price'] ?? null,
                                'price_usd' => $priceData['price_usd'] ?? null,
                            ]);
                        }
                    } else {
                        QuickContributionPrice::create([
                            'quick_contribution_id' => $quickContribution->id,
                            'price' => $priceData['price'] ?? null,
                            'price_usd' => $priceData['price_usd'] ?? null,
                        ]);
                    }
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => __('app.updated successfully'),
                'data' => new QuickContributionResource($quickContribution)
            ], 200);
        } catch (ValidationException $e) {
            $this->logError($request->all(), $e);
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            $this->logError($request->all(), $e);
            return response()->json([
                'message' => 'Error creating quick contrubiton.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Delete a Quick Contribution.
     */
    public function destroy($id)
    {
        try {
            $contribution = QuickContribution::find($id);

            if (!$contribution) {
                return response()->json([
                    'message' => 'Quick Contribution not found.',
                    'data' => null
                ], 404);
            }

            $contribution->clearMediaCollection('quick_contribute');
            $contribution->delete();

            return response()->json([
                'message' => 'Quick Contribution deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting contribution.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the status of a Quick Contribution.
     */
    public function getStatus($id)
    {
        try {
            $quickContribution = QuickContribution::findOrFail($id);

            return response()->json([
                'status' => (bool) $quickContribution->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Quick Contribution not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    protected function logRequest($payload)
    {
        Log::channel('odoo')->info("ODDO API quick contrubiton ", [
            'payload'  => $payload
        ]);
    }

    protected function logError($payload, $error)
    {
        Log::channel('odoo')->error("Odoo API quick contrubiton ", [
            'payload' => $payload,
            'error'   => $error instanceof \Throwable ? $error->getMessage() : $error,
        ]);
    }
}
