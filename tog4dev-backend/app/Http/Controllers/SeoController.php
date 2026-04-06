<?php

namespace App\Http\Controllers;

use App\Models\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seo = Seo::whereNull('model_id')->get();
        return view('admin.seo.index', compact('seo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $seo_type = [
            "home" => "Home",
            "about us" => "About us",
            "contact us" => "Contact us",
            "ngoverse" => "NGOverse"
        ];
        return view('admin.seo.create', compact('seo_type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'model_type'           => 'required|string|max:255',
            'meta_title'           => 'required|string|max:255',
            'meta_description'     => 'required|string|max:500',
            'meta_keywords'        => 'required|string|max:255',
            'meta_title_en'        => 'required|string|max:255',
            'meta_description_en'  => 'required|string|max:500',
            'meta_keywords_en'     => 'required|string|max:255',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $seo = Seo::create([
                'meta_title' => $validatedData['meta_title'],
                'meta_title_en' => $validatedData['meta_title_en'],
                'meta_description' => $validatedData['meta_description'],
                'meta_description_en' => $validatedData['meta_description_en'],
                'meta_keywords' => $validatedData['meta_keywords'],
                'meta_keywords_en' => $validatedData['meta_keywords_en'],
                'model_type' => $validatedData['model_type'],
            ]);

            $seo->addMedia($request->file('image'))->toMediaCollection('seo');
            $seo->addMedia($request->file('image_en'))->toMediaCollection('seo_en');

            DB::commit();

            if(isset($request->save_and_return) && !empty($request->save_and_return)){
                return redirect()->route('seo.index')->with('success', __('app.add successfully'));
            } else{
                return redirect()->back()->with('success', __('app.add successfully'));
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seo $seo)
    {
        $seo_type = [
            "home" => "Home",
            "about us" => "About us",
            "contact us" => "Contact us",
            "ngoverse" => "NGOverse"
        ];

        return view('admin.seo.edit', compact('seo_type', 'seo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $seo = Seo::findOrFail($id);

        // Validate the incoming request
        $validatedData = $request->validate([
            'model_type'           => 'required|string|max:255',
            'meta_title'           => 'required|string|max:255',
            'meta_description'     => 'required|string|max:500',
            'meta_keywords'        => 'required|string|max:255',
            'meta_title_en'        => 'required|string|max:255',
            'meta_description_en'  => 'required|string|max:500',
            'meta_keywords_en'     => 'required|string|max:255',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Update Quick Contribution details
        $seo->update([
            'meta_title' => $validatedData['meta_title'],
            'meta_title_en' => $validatedData['meta_title_en'],
            'meta_description' => $validatedData['meta_description'],
            'meta_description_en' => $validatedData['meta_description_en'],
            'meta_keywords' => $validatedData['meta_keywords'],
            'meta_keywords_en' => $validatedData['meta_keywords_en'],
            'model_type' => $validatedData['model_type'],
        ]);

        if ($request->hasFile('image')) {
            $seo->clearMediaCollection('seo');
            $seo->addMedia($request->file('image'))->toMediaCollection('seo');
        }

        if ($request->hasFile('image_en')) {
            $seo->clearMediaCollection('seo_en');
            $seo->addMedia($request->file('image_en'))->toMediaCollection('seo_en');
        }

        return redirect()->route('seo.index')->with('success', __('app.updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seo $seo)
    {
        $seo->delete();
        if ($seo) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
}
