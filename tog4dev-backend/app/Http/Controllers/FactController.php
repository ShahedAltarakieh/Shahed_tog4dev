<?php

namespace App\Http\Controllers;

use App\Models\Fact;
use Illuminate\Http\Request;

class FactController extends Controller
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
        $data = Fact::filterByCategoryType($this->type)->get();
        return view('admin.facts.index', compact('data'));
    }

    public function show(string $type, string $id)
    {
        $fact = Fact::findOrFail($id);
        $type = $this->type;
        return view('admin.facts.view', compact('fact', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = (new \App\Helpers\Helper)->getCategoriesByType($this->type);
        return view('admin.facts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $type)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => 'required|string',
            'description_en' => 'required|string',
            'category_id' => 'required|integer',
            'status' => 'sometimes|integer',
            'logo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'logo_en' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $messages = __('validation.facts');
        $validated = $request->validate($rules, $messages);
        if(!isset($validated["status"])){
            $validated["status"] = 0;
        }
        $fact = Fact::create($validated);
        $fact->addMedia($request->file('logo'))->toMediaCollection('facts');
        $fact->addMedia($request->file('logo_en'))->toMediaCollection('facts_en');
        if($fact){
            if(isset($request->save_and_return) && !empty($request->save_and_return)){
                return redirect()->route('facts.index', ["type" => $type])->with('success', __('app.add successfully'));
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
        $data = Fact::find($id);
        if (!$data) {
            return redirect()->back();
        } else {
            return view('admin.facts.edit', compact('data', 'categories'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $type, string $id)
    {
        $fact = Fact::findOrFail($id);
        $rules = [
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => 'required|string',
            'description_en' => 'required|string',
            'category_id' => 'required|integer',
            'status' => 'sometimes|integer',
            'logo' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'logo_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $messages = __('validation.facts');
        $validated = $request->validate($rules, $messages);
        if(!isset($validated["status"])){
            $validated["status"] = 0;
        }
        // Update the partner with validated data
        $fact->update($validated);

        if($fact){
            if ($request->hasFile('logo')) {
                $fact->clearMediaCollection('facts');
                $fact->addMedia($request->file('logo'))->toMediaCollection('facts');
            }

            if ($request->hasFile('logo_en')) {
                $fact->clearMediaCollection('facts_en');
                $fact->addMedia($request->file('logo_en'))->toMediaCollection('facts_en');
            }
        }

        return redirect()->route('facts.index', ["type" => $type])->with('success', __('app.updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $type, Fact $fact, Request $request)
    {
        $data = $fact->delete();
        if ($data) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }

    public function change_status($type ,$id)
    {
        $item = Fact::find($id);
        $item->status = !$item->status;
        if($item->save()){
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
}
