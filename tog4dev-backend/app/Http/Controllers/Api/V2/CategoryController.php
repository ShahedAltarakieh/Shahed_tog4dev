<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\Api\V2\CategoryResource;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Get all categories with optional filtering.
     */
    public function index(Request $request)
    {
        try {
            $type = $request->query('type');
            $perPage = $request->query('per-page', 50);
            $orderBy = $request->query('order', 'DESC');

            $typeMapping = [
                'organization' => 1,
                'projects' => 2,
                'crowdfunding' => 3,
            ];

            $query = Category::getActive()->with(['media']);

            if ($type && isset($typeMapping[$type])) {
                $query->where('type', $typeMapping[$type]);
            }

            $query->orderBy('all_option', 'DESC')
                ->orderBy('id', strtoupper($orderBy) === 'DESC' ? 'DESC' : 'ASC');

            $categories = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => CategoryResource::collection($categories),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching categories.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get a single category.
     */
    public function show($id)
    {
        try {
            $category = Category::with(['media'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => new CategoryResource($category),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Category not found.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Store a new category.
     */
    public function store(Request $request)
    {
        try {
            $this->logRequest($request->all());
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'description' => 'nullable|string',
                'description_en' => 'nullable|string',
                'hero_title' => 'nullable|string|max:255',
                'hero_title_en' => 'nullable|string|max:255',
                'hero_description' => 'nullable|string',
                'hero_description_en' => 'nullable|string',
                'type' => 'required|integer|in:1,2,3,4',
                'status' => 'required|integer|in:0,1',
                'all_option' => 'nullable|integer',
                'categoryId' => 'sometimes|integer',
                'x-source-type' => 'nullable|string',
                // 'image_ar' => 'nullable|string',
                // 'image_en' => 'nullable|string',
                // 'image_hero_ar' => 'nullable|string',
                // 'image_hero_en' => 'nullable|string',
            ]);

            $validatedData["source"] = $validatedData["x-source-type"] ?? null;
            $validatedData["need_sync"] = $validatedData["x-source-type"] == "odoo" ? 0 : 1;

            if($validatedData["categoryId"]){
                $validatedData["odoo_id"] = $validatedData["categoryId"];
            } else {
                $validatedData["odoo_id"] = null;
            }

            $category = Category::create($validatedData);

            // if(isset($validatedData["image_ar"]) && $validatedData["image_ar"]){
            //     $category->addMediaFromUrl($validatedData["image_ar"])->toMediaCollection('categories');
            // }
            // if(isset($validatedData["image_en"]) && $validatedData["image_en"]){
            //     $category->addMediaFromUrl($validatedData["image_en"])->toMediaCollection('categories_en');
            // }
            // if(isset($validatedData["image_hero_ar"]) && $validatedData["image_hero_ar"]){
            //     $category->addMediaFromUrl($validatedData["image_hero_ar"])->toMediaCollection('categories_hero');
            // }
            // if(isset($validatedData["image_hero_en"]) && $validatedData["image_hero_en"]){
            //     $category->addMediaFromUrl($validatedData["image_hero_en"])->toMediaCollection('categories_hero_en');
            // }
            
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully.',
                'data' => new CategoryResource($category),
            ], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            $this->logError($request->all(), $e);
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            $this->logError($request->all(), $e);
            return response()->json([
                'message' => 'Error creating category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing category.
     */
    public function update(Request $request, $id)
    {
        try {
            $this->logRequest($request->all());
            $category = Category::findOrFail($id);

            $validatedData = $request->validate([
                'title' => 'sometimes|string|max:255',
                'title_en' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'description_en' => 'nullable|string',
                'hero_title' => 'nullable|string|max:255',
                'hero_title_en' => 'nullable|string|max:255',
                'hero_description' => 'nullable|string',
                'hero_description_en' => 'nullable|string',
                'type' => 'sometimes|integer|in:1,2,3,4',
                'status' => 'sometimes|integer|in:0,1',
                'all_option' => 'nullable|integer',
                'x-source-type' => 'nullable|string',
                // 'image_ar' => 'nullable|string',
                // 'image_en' => 'nullable|string',
                // 'image_hero_ar' => 'nullable|string',
                // 'image_hero_en' => 'nullable|string',
            ]);

            $category->update($validatedData);

            // if(isset($validatedData["image_ar"]) && $validatedData["image_ar"]){
            //     $category->clearMediaCollection('categories');
            //     $category->addMediaFromUrl($validatedData["image_ar"])->toMediaCollection('categories');
            // }
            // if(isset($validatedData["image_en"]) && $validatedData["image_en"]){
            //     $category->clearMediaCollection('categories_en');
            //     $category->addMediaFromUrl($validatedData["image_en"])->toMediaCollection('categories_en');
            // }
            // if(isset($validatedData["image_hero_ar"]) && $validatedData["image_hero_ar"]){
            //     $category->clearMediaCollection('categories_hero');
            //     $category->addMediaFromUrl($validatedData["image_hero_ar"])->toMediaCollection('categories_hero');
            // }
            // if(isset($validatedData["image_hero_en"]) && $validatedData["image_hero_en"]){
            //     $category->clearMediaCollection('categories_hero_en');
            //     $category->addMediaFromUrl($validatedData["image_hero_en"])->toMediaCollection('categories_hero_en');
            // }

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
                'data' => new CategoryResource($category),
            ]);
        } catch (ValidationException $e) {
            $this->logError($request->all(), $e);
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            $this->logError($request->all(), $e);
            return response()->json([
                'message' => 'Error updating category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a category.
     */
    public function destroy($id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Category not found.',
                ], 404);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get the status of a category.
     */
    public function getStatus($id)
    {
        try {
            $category = Category::findOrFail($id);

            return response()->json([
                'success' => true,
                'status' => (bool) $category->status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Category not found.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }


    protected function logRequest($payload)
    {
        Log::channel('odoo')->info("ODDO API Category ", [
            'payload'  => $payload
        ]);
    }

    protected function logError($payload, $error)
    {
        Log::channel('odoo')->error("Odoo API Category ", [
            'payload' => $payload,
            'error'   => $error instanceof \Throwable ? $error->getMessage() : $error,
        ]);
    }
}
