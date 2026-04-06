<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V2\ItemResource;
use App\Models\Item;
use App\Models\ItemPrice;
use App\Models\PriceOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
{
    /**
     * Get all Items.
     */
    public function index(Request $request)
    {
        $type = $request->query('type');
        $items = Item::filterByCategoryType($type)->with(["cartItemsPaid"])->get();

        return response()->json([
            'success' => true,
            'data' => $items
        ]);
    }

    /**
     * Get a single Item.
     */
    public function show($id)
    {
        try {
            $item = Item::with('itemPrices')->find($id);
            if (!$item) {
                return response()->json([
                    'message' => 'Item not found.',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'message' => 'Success',
                'data' => new ItemResource($item)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching item.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new Item.
     */
    public function store(Request $request)
    {
        try{
            $this->logRequest($request->all());

            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'description' => 'required|string',
                'description_en' => 'required|string',
                'location' => 'required|string|max:255',
                'location_en' => 'required|string|max:255',
                'category_id' => 'required|integer',
                'payment_type' => 'required|string',
                'status' => 'required|boolean',
                'amount' => 'required|numeric',
                'amount_usd' => 'required|numeric',
                'prices' => 'nullable|array',
                'prices.*.price' => 'nullable|numeric',
                'prices.*.price_usd' => 'nullable|numeric',
                'x-source-type' => 'nullable|string',
                'productId' => 'nullable|integer',
                'dropdown' => 'nullable|array',
                'dropdown.*.title1' => 'required|string|max:255',
                'dropdown.*.title1_ar' => 'required|string|max:255',
                'dropdown.*.title2' => 'required|string|max:255',
                'dropdown.*.title2_ar' => 'required|string|max:255',
                'dropdown.*.price' => 'required|numeric',
                'dropdown.*.price_usd' => 'required|numeric',
                'dropdown.*.is_default' => 'required|boolean',
                // 'image' => 'nullable|string',
                // 'image_ar' => 'nullable|string',
            ]);

            $validatedData["source"] = $validatedData["x-source-type"] ?? null;
            $validatedData["need_sync"] = $validatedData["x-source-type"] == "odoo" ? 0 : 1;

            if($validatedData["productId"]){
                $validatedData["odoo_id"] = $validatedData["productId"];
            } else {
                $validatedData["odoo_id"] = null;
            }

            $item = Item::create([
                'title' => $validatedData['title'],
                'title_en' => $validatedData['title_en'],
                'description' => $validatedData['description'],
                'description_en' => $validatedData['description_en'],
                'location' => $validatedData['location'],
                'location_en' => $validatedData['location_en'],
                'category_id' => $validatedData['category_id'],
                'payment_type' => $validatedData['payment_type'],
                'status' => $validatedData['status'],
                'amount' => $validatedData['amount'],
                'amount_usd' => $validatedData['amount_usd'],
                "source" => $validatedData["source"],
                "need_sync" => $validatedData["need_sync"],
                "odoo_id" => $validatedData["odoo_id"]
            ]);

            if (!empty($validatedData['prices'])) {
                foreach ($validatedData['prices'] as $priceData) {
                    ItemPrice::create([
                        'item_id' => $item->id,
                        'price' => $priceData['price'] ?? null,
                        'price_en' => $priceData['price_usd'] ?? null,
                    ]);
                }
            }

            if (!empty($validatedData['dropdown'])) {
                foreach ($validatedData['dropdown'] as $option) {
                    PriceOption::create([
                        'item_id'     => $item->id,
                        'd1_option'   => $option['title1_ar'] ?? null,
                        'd1_option_en'=> $option['title1'] ?? null,
                        'd2_option'   => $option['title2_ar'] ?? null,
                        'd2_option_en'=> $option['title2'] ?? null,
                        'price'       => $option['price'] ?? null,
                        'price_en'    => $option['price_usd'] ?? null,
                        'is_default'  => $option['is_default'] ?? 0,
                    ]);
                }
            }


            // if($validatedData["image"]){
            //     try {
            //         $item->addMediaFromUrl($validatedData["image"])->toMediaCollection('items_en');
            //     } catch (\Exception $e) {
            //         // Log error but don't break main request
            //         \Log::error('Image upload failed: '.$e->getMessage());
            //     }
            // }
            // if($validatedData["image_ar"]){
            //     try {
            //         $item->addMediaFromUrl($validatedData["image_ar"])->toMediaCollection('items');
            //     } catch (\Exception $e) {
            //         // Log error but don't break main request
            //         \Log::error('Image upload failed: '.$e->getMessage());
            //     }
            // }

            return response()->json([
                'success' => true,
                'message' => __('app.add successfully'),
                'data' => new ItemResource($item)
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
                'message' => 'Error create item.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Update an existing Item.
     */
    public function update(Request $request, $id)
    {
        try{
            $this->logRequest($request->all());
            $item = Item::findOrFail($id);

            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'description' => 'required|string',
                'description_en' => 'required|string',
                'location' => 'required|string|max:255',
                'location_en' => 'required|string|max:255',
                'category_id' => 'required|integer',
                'payment_type' => 'required|string',
                'status' => 'required|boolean',
                'amount' => 'required|numeric',
                'amount_usd' => 'required|numeric',
                'prices' => 'nullable|array',
                'prices.*.priceId' => 'nullable|integer|exists:item_prices,id',
                'prices.*.price' => 'nullable|numeric',
                'prices.*.price_usd' => 'nullable|numeric',
                'dropdown' => 'nullable|array',
                'dropdown.*.dropdownId' => 'nullable|integer|exists:price_options,id',
                'dropdown.*.title1' => 'required|string|max:255',
                'dropdown.*.title1_ar' => 'required|string|max:255',
                'dropdown.*.title2' => 'required|string|max:255',
                'dropdown.*.title2_ar' => 'required|string|max:255',
                'dropdown.*.price' => 'required|numeric',
                'dropdown.*.price_usd' => 'required|numeric',
                'dropdown.*.is_default' => 'required|boolean',
                // 'image' => 'nullable|string',
                // 'image_ar' => 'nullable|string',
            ]);

            $item->update([
                'title' => $validatedData['title'],
                'title_en' => $validatedData['title_en'],
                'description' => $validatedData['description'],
                'description_en' => $validatedData['description_en'],
                'location' => $validatedData['location'],
                'location_en' => $validatedData['location_en'],
                'category_id' => $validatedData['category_id'],
                'payment_type' => $validatedData['payment_type'],
                'status' => $validatedData['status'],
                'amount' => $validatedData['amount'],
                'amount_usd' => $validatedData['amount_usd'],
            ]);

            if (!empty($validatedData['prices'])) {
                foreach ($validatedData['prices'] as $priceData) {
                    if (isset($priceData['priceId'])) {
                        $price = ItemPrice::find($priceData['priceId']);
                        if ($price) {
                            $price->update([
                                'price' => $priceData['price'] ?? null,
                                'price_en' => $priceData['price_usd'] ?? null,
                            ]);
                        }
                    } else {
                        ItemPrice::create([
                            'item_id' => $item->id,
                            'price' => $priceData['price'] ?? null,
                            'price_en' => $priceData['price_usd'] ?? null,
                        ]);
                    }
                }
            }

            if (!empty($validatedData['dropdown'])) {
                foreach ($validatedData['dropdown'] as $optionData) {
                    if (isset($optionData['dropdownId'])) {
                        // Update existing record
                        $priceOption = PriceOption::find($optionData['dropdownId']);
                        if ($priceOption) {
                            $priceOption->update([
                                'item_id'      => $item->id,
                                'd1_option'    => $optionData['title1_ar'] ?? null,
                                'd1_option_en' => $optionData['title1'] ?? null,
                                'd2_option'    => $optionData['title2_ar'] ?? null,
                                'd2_option_en' => $optionData['title2'] ?? null,
                                'price'        => $optionData['price'] ?? null,
                                'price_en'     => $optionData['price_usd'] ?? null,
                                'is_default'   => $optionData['is_default'] ?? 0,
                            ]);
                        }
                    } else {
                        // Create new record
                        PriceOption::create([
                            'item_id'      => $item->id,
                            'd1_option'    => $optionData['title1_ar'] ?? null,
                            'd1_option_en' => $optionData['title1'] ?? null,
                            'd2_option'    => $optionData['title2_ar'] ?? null,
                            'd2_option_en' => $optionData['title2'] ?? null,
                            'price'        => $optionData['price'] ?? null,
                            'price_en'     => $optionData['price_usd'] ?? null,
                            'is_default'   => $optionData['is_default'] ?? 0,
                        ]);
                    }
                }
            }


            // if($validatedData["image"]){
            //     try {
            //         $item->clearMediaCollection('items_en');
            //         $item->addMediaFromUrl($validatedData["image"])->toMediaCollection('items_en');
            //     } catch (\Exception $e) {
            //         // Log error but don't break main request
            //         \Log::error('Image upload failed: '.$e->getMessage());
            //     }
            // }
            // if($validatedData["image_ar"]){
            //     try {
            //         $item->clearMediaCollection('items');
            //         $item->addMediaFromUrl($validatedData["image_ar"])->toMediaCollection('items');
            //     } catch (\Exception $e) {
            //         // Log error but don't break main request
            //         \Log::error('Image upload failed: '.$e->getMessage());
            //     }
            // }

            return response()->json([
                'success' => true,
                'message' => __('app.updated successfully'),
                'data' => new ItemResource($item)
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
                'message' => 'Error update item.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete an Item.
     */
    public function destroy($id)
    {
        try {
            $item = Item::find($id);

            if (!$item) {
                return response()->json([
                    'message' => 'Item not found.',
                    'data' => null
                ], 404);
            }

            $item->clearMediaCollection('items');
            $item->delete();

            return response()->json([
                'message' => 'Item deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting item.',
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
            $Item = Item::findOrFail($id);

            return response()->json([
                'status' => (bool) $Item->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Item not found.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    protected function logRequest($payload)
    {
        Log::channel('odoo')->info("ODDO API item ", [
            'payload'  => $payload
        ]);
    }

    protected function logError($payload, $error)
    {
        Log::channel('odoo')->error("Odoo API item ", [
            'payload' => $payload,
            'error'   => $error instanceof \Throwable ? $error->getMessage() : $error,
        ]);
    }
}
