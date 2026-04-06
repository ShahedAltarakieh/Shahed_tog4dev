<?php

namespace App\Http\Controllers;

use App\Models\Sliders;
use Illuminate\Http\Request;

class SlidersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Sliders::all();
        return view('admin.sliders.index', compact('data'));
    }

    public function show($slider)
    {
        $data = Sliders::findOrFail($slider); // Fetch the slider data
        return view('admin.sliders.view', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.sliders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $rules = [
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => 'required|string',
            'description_en' => 'required|string',
            'status' => 'sometimes|integer|in:0,1',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_tablet' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_mobile' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'logo' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'logo_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $messages = __('validation.sliders');
        $validated = $request->validate($rules, $messages);
        if (!isset($validated["status"])) {
            $validated["status"] = 0;
        }
        // Create the slider
        $slider = Sliders::create($validated);
        $slider->addMedia($request->file('image'))->toMediaCollection('sliders');

        if ($request->hasFile('image_tablet')) {
            $slider->addMedia($request->file('image_tablet'))->toMediaCollection('sliders_tablet');
        }

        if ($request->hasFile('image_mobile')) {
            $slider->addMedia($request->file('image_mobile'))->toMediaCollection('sliders_mobile');
        }
        
        if ($request->hasFile('image_en')) {
            $slider->addMedia($request->file('image_en'))->toMediaCollection('sliders_en');
        }

        if ($request->hasFile('logo')) {
            $slider->addMedia($request->file('logo'))->toMediaCollection('sliders_logo');
        }

        if ($request->hasFile('logo_en')) {
            $slider->addMedia($request->file('logo_en'))->toMediaCollection('sliders_logo_en');
        }

        //$slider->addMedia($request->file('logo'))->toMediaCollection('sliders_logo');
       // $slider->addMedia($request->file('logo_en'))->toMediaCollection('sliders_logo_en');
        if($slider){
            if(isset($request->save_and_return) && !empty($request->save_and_return)){
                return redirect()->route('sliders.index')->with('success', __('app.add successfully'));
            } else{
                return redirect()->back()->with('success', __('app.add successfully'));
            }
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Sliders::find($id);
        if (!$data) {
            return redirect()->back();
        } else {
            return view('admin.sliders.edit', compact('data'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the slider by ID or return a 404 error
        $slider = Sliders::findOrFail($id);

        // Validate the request data
        $rules = [
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => 'required|string',
            'description_en' => 'required|string',
            'status' => 'sometimes|integer|in:0,1',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_tablet' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_mobile' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'logo' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'logo_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $messages = __('validation.sliders');
        $validated = $request->validate($rules, $messages);
        if (!isset($validated["status"])) {
            $validated["status"] = 0;
        }
        // Update the slider with validated data
        $slider->update($validated);

        if ($slider) {
            if ($request->hasFile('image')) {
                $slider->clearMediaCollection('sliders');
                $slider->addMedia($request->file('image'))->toMediaCollection('sliders');
            }

            if ($request->hasFile('image_tablet')) {
                $slider->clearMediaCollection('sliders_tablet');
                $slider->addMedia($request->file('image_tablet'))->toMediaCollection('sliders_tablet');
            }

            if ($request->hasFile('image_mobile')) {
                $slider->clearMediaCollection('sliders_mobile');
                $slider->addMedia($request->file('image_mobile'))->toMediaCollection('sliders_mobile');
            }

            if ($request->hasFile('image_en')) {
                $slider->clearMediaCollection('sliders_en');
                $slider->addMedia($request->file('image_en'))->toMediaCollection('sliders_en');
            }

            if ($request->hasFile('logo')) {
                $slider->clearMediaCollection('sliders_logo');
                $slider->addMedia($request->file('logo'))->toMediaCollection('sliders_logo');
            }

            if ($request->hasFile('logo_en')) {
                $slider->clearMediaCollection('sliders_logo_en');
                $slider->addMedia($request->file('logo_en'))->toMediaCollection('sliders_logo_en');
            }

            return redirect()->route('sliders.index')->with('success', __('app.updated successfully!'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sliders $slider, Request $request)
    {
        $data = $slider->delete();
        if ($data) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }

    public function change_status($id)
    {
        $item = Sliders::find($id);
        $item->status = !$item->status;
        if($item->save()){
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
}
