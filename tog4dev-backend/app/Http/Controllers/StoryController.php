<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\Category;
use Illuminate\Http\Request;

class StoryController extends Controller
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
        $data = Story::filterByCategoryType($this->type)->get();
        return view('admin.stories.index', compact('data'));
    }

    public function show(string $type, string $id)
    {
        $story = Story::findOrFail($id);
        $type = $this->type;
        return view('admin.stories.view', compact('story', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = (new \App\Helpers\Helper)->getCategoriesByType($this->type);
        return view('admin.stories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $type)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'status' => 'required|boolean',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_tablet' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_mobile' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $messages = __('validation.stories');
        $validated = $request->validate($rules, $messages);
        if(!isset($validated["status"])){
            $validated["status"] = 0;
        }

        $story = Story::create($validated);

        // Add media (image) if provided
        $story->addMedia($request->file('image'))->toMediaCollection('stories');
        if ($request->hasFile('image_tablet')) {
            $story->addMedia($request->file('image_tablet'))->toMediaCollection('stories_tablet');
        }
        if ($request->hasFile('image_mobile')) {
            $story->addMedia($request->file('image_mobile'))->toMediaCollection('stories_mobile');
        }
        if ($request->hasFile('image_en')) {
            $story->addMedia($request->file('image_en'))->toMediaCollection('stories_en');
        }

        if ($story) {
            if(isset($request->save_and_return) && !empty($request->save_and_return)){
                return redirect()->route('stories.index', ["type" => $type])->with('success', __('app.add successfully'));
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
        $data = Story::find($id);
        $categories = (new \App\Helpers\Helper)->getCategoriesByType($this->type);
        if (!$data) {
            return redirect()->back();
        } else {
            return view('admin.stories.edit', compact('data', 'categories'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $type, string $id)
    {
        $story = Story::findOrFail($id);
        $rules = [
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'status' => 'required|boolean',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_tablet' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_mobile' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $messages = __('validation.stories');
        $validated = $request->validate($rules, $messages);
        if(!isset($validated["status"])){
            $validated["status"] = 0;
        }
        // Update the story with validated data
        $story->update($validated);

        if ($story) {
            if ($request->hasFile('image')) {
                $story->clearMediaCollection('stories');
                $story->addMedia($request->file('image'))->toMediaCollection('stories');
            }

            if ($request->hasFile('image_tablet')) {
                $story->clearMediaCollection('stories_tablet');
                $story->addMedia($request->file('image_tablet'))->toMediaCollection('stories_tablet');
            }
            if ($request->hasFile('image_mobile')) {
                $story->clearMediaCollection('stories_mobile');
                $story->addMedia($request->file('image_mobile'))->toMediaCollection('stories_mobile');
            }

            if ($request->hasFile('image_en')) {
                $story->clearMediaCollection('stories_en');
                $story->addMedia($request->file('image_en'))->toMediaCollection('stories_en');
            }
            return redirect()->route('stories.index', ["type" => $type])->with('success', __('app.updated successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $type, Story $story, Request $request)
    {
        $data = $story->delete();

        if ($data) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }

    public function change_status($type ,$id)
    {
        $item = Story::find($id);
        $item->status = !$item->status;
        if($item->save()){
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
}
