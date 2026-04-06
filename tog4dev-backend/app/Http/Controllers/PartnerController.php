<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
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
        $data = Partner::filterByCategoryType($this->type)->get();
        return view('admin.partners.index', compact('data'));

    }

    public function show(string $type, string $id)
    {
        $partner = Partner::findOrFail($id);
        $type = $this->type;
        return view('admin.partners.view', compact('partner', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = (new \App\Helpers\Helper)->getCategoriesByType($this->type);
        return view('admin.partners.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $type, Request $request)
    {
        // Validate the request data
        $rules = [
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'status' => 'sometimes|integer|in:0,1',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $messages = __('validation.partners');
        $validated = $request->validate($rules, $messages);
        if(!isset($validated["status"])){
            $validated["status"] = 0;
        }
        // Create the partner
        $partner = Partner::create($validated);
        $partner->addMedia($request->file('image'))->toMediaCollection('partners');
        $partner->addMedia($request->file('image_en'))->toMediaCollection('partners_en');
        if($partner){
            if(isset($request->save_and_return) && !empty($request->save_and_return)){
                return redirect()->route('partners.index', ["type" => $type])->with('success', __('app.add successfully'));
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
        $categories = (new \App\Helpers\Helper)->getCategoriesByType($this->type);
        $data = Partner::find($id);
        if (!$data) {
            return redirect()->back();
        } else {
            return view('admin.partners.edit', compact('data', 'categories'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $type, string $id)
    {
        // Find the partner by ID or return a 404 error
        $partner = Partner::findOrFail($id);

        // Validate the request data
        $rules = [
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'status' => 'sometimes|integer|in:0,1',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $messages = __('validation.partners');
        $validated = $request->validate($rules, $messages);
        if(!isset($validated["status"])){
            $validated["status"] = 0;
        }
        // Update the partner with validated data
        $partner->update($validated);

        if($partner){
            if ($request->hasFile('image')) {
                $partner->clearMediaCollection('partners');
                $partner->addMedia($request->file('image'))->toMediaCollection('partners');
            }

            if ($request->hasFile('image_en')) {
                $partner->clearMediaCollection('partners_en');
                $partner->addMedia($request->file('image_en'))->toMediaCollection('partners_en');
            }
            return redirect()->route('partners.index', ["type" => $type])->with('success', __('app.updated successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $type, Partner $partner, Request $request)
    {
        $data = $partner->delete();
        if ($data) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }

    public function change_status($type ,$id)
    {
        $item = Partner::find($id);
        $item->status = !$item->status;
        if($item->save()){
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
}
