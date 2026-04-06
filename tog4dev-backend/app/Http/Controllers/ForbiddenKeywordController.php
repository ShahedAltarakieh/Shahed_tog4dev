<?php

namespace App\Http\Controllers;

use App\Models\ForbiddenKeyword;
use App\Services\NameValidationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ForbiddenKeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $keywords = ForbiddenKeyword::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $keywords
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'keyword' => 'required|string|max:255|unique:forbidden_keywords,keyword'
        ]);

        $keyword = ForbiddenKeyword::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Forbidden keyword created successfully',
            'data' => $keyword
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ForbiddenKeyword $forbiddenKeyword): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $forbiddenKeyword
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ForbiddenKeyword $forbiddenKeyword): JsonResponse
    {
        $validated = $request->validate([
            'keyword' => 'required|string|max:255|unique:forbidden_keywords,keyword,' . $forbiddenKeyword->id
        ]);

        $forbiddenKeyword->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Forbidden keyword updated successfully',
            'data' => $forbiddenKeyword
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ForbiddenKeyword $forbiddenKeyword): JsonResponse
    {
        $forbiddenKeyword->delete();

        return response()->json([
            'success' => true,
            'message' => 'Forbidden keyword deleted successfully'
        ]);
    }

    /**
     * Test a name against forbidden keywords
     */
    public function testName(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255'
        ]);

        $nameValidationService = app(NameValidationService::class);
        $errors = $nameValidationService->validateNames(
            $validated['first_name'], 
            $validated['last_name']
        );

        return response()->json([
            'success' => empty($errors),
            'errors' => $errors,
            'message' => empty($errors) ? 'Names are valid' : 'Names contain forbidden keywords'
        ]);
    }
}
