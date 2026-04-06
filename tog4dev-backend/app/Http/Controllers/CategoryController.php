<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Seo;
use Illuminate\Http\Request;
use App\Jobs\OdooJobs\SendCategoryToOdooJob;

class CategoryController extends Controller
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
        // Map the type to the respective scope
        $data = match ($this->type) {
            'projects' => Category::getProjects()->get(),
            'organization' => Category::getOrganization()->get(),
            'crowdfunding' => Category::getCrowdfunding()->get(),
            'home' => Category::getHome()->get(),
            default => Category::all(), // Fallback if no valid type is provided
        };
        return view('admin.categories.index', compact('data'));
    }

    public function show(string $type, string $id)
    {
        $category = Category::findOrFail($id);
        $type = $this->type;
        return view('admin.categories.view', compact('category', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
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
            'description' => 'required|string',
            'description_en' => 'required|string',
            'hero_title' => 'required|string|max:255',
            'hero_title_en' => 'required|string|max:255',
            'hero_description' => 'required|string',
            'hero_description_en' => 'required|string',
            'status' => 'sometimes|integer|in:0,1',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hero_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hero_image_tablet' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hero_image_mobile' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hero_image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $messages = __('validation.categories');
        $validated = $request->validate($rules, $messages);
        if (!isset($validated["status"])) {
            $validated["status"] = 0;
        }

        $validated["type"] = Helper::getTypes($type);
        $validated["need_sync"] = 1;
        // Create the category
        $category = Category::create($validated);

        // Add media (image) if provider
	    if ($request->hasFile('image')) {
            $category->addMedia($request->file('image'))->toMediaCollection('categories');
        }

        if ($request->hasFile('image_en')) {
            $category->addMedia($request->file('image_en'))->toMediaCollection('categories_en');
        }

        $category->addMedia($request->file('hero_image'))->toMediaCollection('categories_hero');

	    if ($request->hasFile('hero_image_tablet')) {
            $category->addMedia($request->file('hero_image_tablet'))->toMediaCollection('categories_hero_tablet');
        }

	    if ($request->hasFile('hero_image_mobile')) {
            $category->addMedia($request->file('hero_image_mobile'))->toMediaCollection('categories_hero_mobile');
        }

        if ($request->hasFile('hero_image_en')) {
            $category->addMedia($request->file('hero_image_en'))->toMediaCollection('categories_hero_en');
        }

        if ($category) {
            SendCategoryToOdooJob::dispatch($category->id)->delay(2);
            if (isset($request->save_and_return) && !empty($request->save_and_return)) {
                return redirect()->route('categories.index', ["type" => $type])->with('success', __('app.add successfully'));
            } else {
                return redirect()->back()->with('success', __('app.add successfully'));
            }
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $type, string $id)
    {
        $data = Category::find($id);
        if (!$data) {
            return redirect()->back();
        } else {
            return view('admin.categories.edit', compact('data'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $type, string $id, Request $request)
    {
        // Find the category by ID or return a 404 error
        $category = Category::findOrFail($id);

        // Validate the request data
        $rules = [
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => 'required|string',
            'description_en' => 'required|string',
            'hero_title' => 'required|string|max:255',
            'hero_title_en' => 'required|string|max:255',
            'hero_description' => 'required|string',
            'hero_description_en' => 'required|string',
            'status' => 'sometimes|required|integer',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hero_image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hero_image_tablet' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hero_image_mobile' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hero_image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];

        $messages = __('validation.categories');
        $validated = $request->validate($rules, $messages);
        if (!isset($validated["status"])) {
            $validated["status"] = 0;
        }
        $validated["need_sync"] = 1;
        // Update the category with validated data
        $category->update($validated);

        if ($category) {
            if ($request->hasFile('image')) {
                $category->clearMediaCollection('categories');
                $category->addMedia($request->file('image'))->toMediaCollection('categories');
            }

            if ($request->hasFile('image_en')) {
                $category->clearMediaCollection('categories_en');
                $category->addMedia($request->file('image_en'))->toMediaCollection('categories_en');
            }

            if ($request->hasFile('hero_image_tablet')) {
                $category->clearMediaCollection('categories_hero_tablet');
                $category->addMedia($request->file('hero_image_tablet'))->toMediaCollection('categories_hero_tablet');
            }

            if ($request->hasFile('hero_image_mobile')) {
                $category->clearMediaCollection('categories_hero_mobile');
                $category->addMedia($request->file('hero_image_mobile'))->toMediaCollection('categories_hero_mobile');
            }

            if ($request->hasFile('hero_image')) {
                $category->clearMediaCollection('categories_hero');
                $category->addMedia($request->file('hero_image'))->toMediaCollection('categories_hero');
            }

            if ($request->hasFile('hero_image_en')) {
                $category->clearMediaCollection('categories_hero_en');
                $category->addMedia($request->file('hero_image_en'))->toMediaCollection('categories_hero_en');
            }
            SendCategoryToOdooJob::dispatch($category->id)->delay(2);
            return redirect()->route('categories.index', ["type" => $type])->with('success', __('app.updated successfully'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $type, Category $category, Request $request)
    {
        $data = $category->delete();

        if ($data) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }

    public function change_status($type, $id)
    {
        $item = Category::find($id);
        $item->status = !$item->status;
        $item->need_sync = 1;
        SendCategoryToOdooJob::dispatch($item->id)->delay(2);
        if($item->save()){
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }

    public function seo($type, Category $category)
    {
        $seo = $category->seo ?? new Seo();
        return view('admin.categories.seo', compact('category', 'seo'));
    }   

    public function update_seo(Request $request, $type, Category $category)
    {
        // Validation for incoming request
        $request->validate([
            'meta_title'           => 'required|string|max:255',
            'meta_description'     => 'required|string|max:500',
            'meta_keywords'        => 'required|string|max:255',
            'meta_title_en'        => 'required|string|max:255',
            'meta_description_en'  => 'required|string|max:500',
            'meta_keywords_en'     => 'required|string|max:255',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $seo = $category->seo ?? new Seo();
        $seo->fill($request->all());
        $seo->model_id = $category->id;
        $seo->model_type = "App\Models\Category";
        $seo->save();

        if ($request->hasFile('image')) {
            $seo->clearMediaCollection('seo');
            $seo->addMedia($request->file('image'))->toMediaCollection('seo');
        }

        if ($request->hasFile('image_en')) {
            $seo->clearMediaCollection('seo_en');
            $seo->addMedia($request->file('image_en'))->toMediaCollection('seo_en');
        }
        return redirect()->route('categories.index', ["type" => $type])->with('success', __('app.updated successfully'));
    }
}
