<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use PHPUnit\Util\Test;

class TestimonialController extends Controller
{
    protected $type;

    public function __construct(Request $request)
    {
        // Set the type from the route parameter
        $this->type = $request->route('type');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Testimonial::filterByCategoryType($this->type)->get();
        return view('admin.testimonials.index', compact('data'));
    }

    public function show(string $type, string $id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $type = $this->type;
        return view('admin.testimonials.view', compact('testimonial', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = (new \App\Helpers\Helper)->getCategoriesByType($this->type);
        return view('admin.testimonials.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $type)
    {
        // Validate the request data
        $rules = [
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'description_en' => 'required|string|max:1000',
            'category_id' => 'required|integer|exists:categories,id',
            'status' => 'sometimes|integer|in:0,1',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_tablet' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_mobile' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'location' => 'required|string|max:255',
            'location_en' => 'required|string|max:255',
        ];

        $messages = __('validation.testimonials');
        $validated = $request->validate($rules, $messages);
        if(!isset($validated["status"])){
            $validated["status"] = 0;
        }
        // Create the partner
        $partner = Testimonial::create($validated);
        $partner->addMedia($request->file('image'))->toMediaCollection('testimonials');
        if ($request->hasFile('image_tablet')) {
            $partner->addMedia($request->file('image_tablet'))->toMediaCollection('testimonials_tablet');
        }

        if ($request->hasFile('image_mobile')) {
            $partner->addMedia($request->file('image_mobile'))->toMediaCollection('testimonials_mobile');
        }
        if ($request->hasFile('image_en')) {
            $partner->addMedia($request->file('image_en'))->toMediaCollection('testimonials_en');
        }
        if($partner){
            if(isset($request->save_and_return) && !empty($request->save_and_return)){
                return redirect()->route('testimonials.index', ["type" => $type])->with('success', __('app.add successfully'));
            } else{
                return redirect()->back()->with('success', __('app.add successfully'));
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $type, string $id)
    {
        $data = Testimonial::find($id);
        $categories = (new \App\Helpers\Helper)->getCategoriesByType($this->type);
        if (!$data) {
            return redirect()->back();
        } else {
            return view('admin.testimonials.edit', compact('data', 'categories'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $type, string $id)
    {
        // Find the partner by ID or return a 404 error
        $partner = Testimonial::findOrFail($id);

        // Validate the request data
        $rules = [
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'description_en' => 'required|string|max:1000',
            'category_id' => 'required|integer|exists:categories,id',
            'status' => 'sometimes|integer|in:0,1',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_tablet' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_mobile' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'location' => 'required|string|max:255',
            'location_en' => 'required|string|max:255',
        ];

        $messages = __('validation.testimonials');
        $validated = $request->validate($rules, $messages);
        if(!isset($validated["status"])) {
            $validated["status"] = 0;
        }
        // Update the partner with validated data
        $partner->update($validated);

        if($partner){
            if ($request->hasFile('image')) {
                $partner->clearMediaCollection('testimonials');
                $partner->addMedia($request->file('image'))->toMediaCollection('testimonials');
            }

            if ($request->hasFile('image_tablet')) {
                $partner->clearMediaCollection('testimonials_tablet');
                $partner->addMedia($request->file('image_tablet'))->toMediaCollection('testimonials_tablet');
            }

            if ($request->hasFile('image_mobile')) {
                $partner->clearMediaCollection('testimonials_mobile');
                $partner->addMedia($request->file('image_mobile'))->toMediaCollection('testimonials_mobile');
            }

            if ($request->hasFile('image_en')) {
                $partner->clearMediaCollection('testimonials_en');
                $partner->addMedia($request->file('image_en'))->toMediaCollection('testimonials_en');
            }
            return redirect()->route('testimonials.index', ["type" => $type])->with('success', __('app.updated successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $type, Testimonial $testimonial, Request $request)
    {
        $data = $testimonial->delete();
        if ($data) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }

    public function change_status($type ,$id)
    {
        $item = Testimonial::find($id);
        $item->status = !$item->status;
        if($item->save()){
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
}
