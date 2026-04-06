<?php

namespace App\Http\Controllers;

use App\Models\AdditionalInfo;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Seo;
use App\Models\PriceOption;
use App\Models\OrderingItem;
use Illuminate\Http\Request;
use App\Jobs\OdooJobs\SendItemToOdooJob;

class ItemController extends Controller
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
        $data = Item::filterByCategoryType($this->type)->with(["cartItemsPaid"])->get();
        return view('admin.items.index', compact('data'));
    }

    public function getHomeOnly(){
        $this->type = "home";
        $data = Item::where("show_in_home", 1)->with(["cartItemsPaid"])->get();
        return view('admin.items.home_index', compact('data'));
    }

    public function sorting()
    {
        $type = (new \App\Helpers\Helper)->getTypes($this->type);
        if($this->type == "home"){
            $data = Item::select('items.*', 'ordering_item.sort_order')
                ->leftJoin('ordering_item', function ($join) use ($type) {
                    $join->on('ordering_item.item_id', '=', 'items.id')
                        ->where('ordering_item.type', '=', $type);   // IMPORTANT FIX
                })
                ->where("show_in_home", 1)
                ->where("status", true)
                ->orderByRaw("
                    CASE WHEN ordering_item.sort_order IS NULL THEN 1 ELSE 0 END ASC,
                    ordering_item.sort_order ASC
                ")
                ->get();
        } else {
            $data = Item::select('items.*', 'ordering_item.sort_order')
                ->leftJoin('ordering_item', function ($join) use ($type) {
                    $join->on('ordering_item.item_id', '=', 'items.id')
                        ->where('ordering_item.type', '=', $type);   // IMPORTANT FIX
                })
                ->filterByCategoryType($this->type)
                ->where("status", true)
                ->orderByRaw("
                    CASE WHEN ordering_item.sort_order IS NULL THEN 1 ELSE 0 END ASC,
                    ordering_item.sort_order ASC
                ")
                ->get();
        }
        return view('admin.items.sorting', compact('data'));
    }

    public function storeSorting(Request $request)
    {
        $orderData = $request->input('order', []);
        $type = (new \App\Helpers\Helper)->getTypes($this->type);
        foreach ($orderData as $item) {
            OrderingItem::updateOrCreate(
                ['item_id' => $item['id'], "type" => $type],
                ['sort_order' => $item['sort_order']]
            );
        }

        $url = route('items.index', ["type" => $this->type]);
        return response()->json(['status' => 'success', 'url' => $url]);
    }

    public function show(string $type, string $id)
    {
        $data = Item::findOrFail($id);
        $type = $this->type;
        return view('admin.items.view', compact('data', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = (new \App\Helpers\Helper)->getCategoriesByType(type: $this->type);
        $type = $this->type;
        $analyticـaccounts = Setting::where('key', 'analyticـaccount')->get();
        return view('admin.items.create', compact('categories', 'type', 'analyticـaccounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $type, Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'beneficiaries_message' => 'nullable|string',
            'beneficiaries_message_en' => 'nullable|string',
            'description_after_payment' => 'nullable|string',
            'description_after_payment_en' => 'nullable|string',
            'location' => 'required|string|max:255',
            'location_en' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'payment_type' => 'sometimes|string|in:Both,One-Time,Subscription',
            'status' => 'sometimes|integer',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_tablet' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_mobile' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'array',
            'price.*' => 'nullable|numeric|min:0',
            'price_en' => 'array',
            'price_en.*' => 'nullable|numeric|min:0',
        ];

        if ($type != "organization") {
            $rules["amount"] = 'required|numeric|min:1';
            $rules["amount_usd"] = 'required|numeric|min:1';
            $rules["analyticـaccount"] = 'required|integer';
        }

        if ($type == "crowdfunding") {
            $rules["single_price"] = 'nullable|numeric|min:1';
            $rules["single_price_usd"] = 'nullable|numeric|min:1';
        }

        $messages = __('validation.items');
        $validated = $request->validate($rules, $messages);
        if (!isset($validated["status"])) {
            $validated["status"] = 0;
        }
        if ($type == "organization") {
            $validated["amount"] = 1;
            $validated["amount_usd"] = 1;
	    }
        if(!isset($validated["payment_type"])){
            $validated["payment_type"] = "Both";
        }
        $validated["has_beneficiary"] = $type == "projects" ? $request->boolean('has_beneficiary') : false;
        $validated["need_sync"] = 1;
        // Create the item
        $item = Item::create($validated);

        // Add media (image) if provided
        if ($request->hasFile('image')) {
            $item->addMedia($request->file('image'))->toMediaCollection('items');
        }
        if ($request->hasFile('image_tablet')) {
            $item->addMedia($request->file('image_tablet'))->toMediaCollection('items_tablet');
        }
        if ($request->hasFile('image_mobile')) {
            $item->addMedia($request->file('image_mobile'))->toMediaCollection('items_mobile');
        }
        if ($request->hasFile('image_en')) {
            $item->addMedia($request->file('image_en'))->toMediaCollection('items_en');
        }

        if (isset($validated['price']) && isset($validated['price_en']) && count($validated['price']) == count($validated['price_en'])) {
            // Loop through the price array and create item prices with both Arabic and English prices
            foreach ($validated['price'] as $index => $price) {
                $price_en = $validated['price_en'][$index] ?? null; // Get the corresponding English price

                // Insert both price (Arabic) and price_en (English) into the item_prices table
                if (!is_null($price) && !is_null($price_en)) {
                    $item->itemPrices()->create([
                        'price' => $price,
                        'price_en' => $price_en,
                    ]);
                }
            }
        }

        // Add price options if specified
        if ($request->type == "projects") {
            $isDefault = $request->input('is_default', null); // Fetch is_default input, null if not selected

            $options = [
                [
                    "prices_option" => $request->prices_option_1,
                    "d1_option" => $request->dropdown_1_option_1,
                    "d1_option_en" => $request->dropdown_1_option_1_en,
                    "d2_option" => $request->dropdown_2_option_1,
                    "d2_option_en" => $request->dropdown_2_option_1_en,
                    "price" => $request->price_1_jod,
                    "price_en" => $request->price_1_usd,
                ],
                [
                    "prices_option" => $request->prices_option_2,
                    "d1_option" => $request->dropdown_1_option_1,
                    "d1_option_en" => $request->dropdown_1_option_1_en,
                    "d2_option" => $request->dropdown_2_option_2,
                    "d2_option_en" => $request->dropdown_2_option_2_en,
                    "price" => $request->price_2_jod,
                    "price_en" => $request->price_2_usd,
                ],
                [
                    "prices_option" => $request->prices_option_3,
                    "d1_option" => $request->dropdown_1_option_2,
                    "d1_option_en" => $request->dropdown_1_option_2_en,
                    "d2_option" => $request->dropdown_2_option_1,
                    "d2_option_en" => $request->dropdown_2_option_1_en,
                    "price" => $request->price_3_jod,
                    "price_en" => $request->price_3_usd,
                ],
                [
                    "prices_option" => $request->prices_option_4,
                    "d1_option" => $request->dropdown_1_option_2,
                    "d1_option_en" => $request->dropdown_1_option_2_en,
                    "d2_option" => $request->dropdown_2_option_2,
                    "d2_option_en" => $request->dropdown_2_option_2_en,
                    "price" => $request->price_4_jod,
                    "price_en" => $request->price_4_usd,
                ],
            ];

            // Loop through the options and create entries
            foreach ($options as $index => $option) {
                if (!empty($option['prices_option'])) {
                    $item->priceOptions()->create([
                        "d1_option" => $option['d1_option'],
                        "d1_option_en" => $option['d1_option_en'],
                        "d2_option" => $option['d2_option'],
                        "d2_option_en" => $option['d2_option_en'],
                        "price" => $option['price'],
                        "price_en" => $option['price_en'],
                        "is_default" => ($isDefault == ($index + 1)) ? 1 : 0, // Mark selected one as default
                    ]);
                }
            }

            // Check if no option was marked as default, set the first one
            if (is_null($isDefault)) {
                $firstOption = $item->priceOptions()->first();
                if ($firstOption) {
                    $firstOption->update(['is_default' => 1]);
                }
            }
        }

        if (isset($request->save_and_return) && !empty($request->save_and_return)) {
            SendItemToOdooJob::dispatch($item->id)->delay(2);
            return redirect()->route('items.index', ["type" => $type])->with('success', __('app.add successfully'));
        } else {
            return redirect()->back()->with('success', __('app.add successfully'));
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $type, string $id)
    {
        $data = Item::find($id);
        $categories = (new \App\Helpers\Helper)->getCategoriesByType(type: $this->type);
        $options = $data->priceOptions->toArray();

        // Extract unique options for d1 and d2
        $d1Options = collect($options)->pluck('d1_option')->unique()->values();
        $d2Options = collect($options)->pluck('d2_option')->unique()->values();

        // Combine both options with their translations
        $d1OptionsWithTranslations = collect($options)
            ->map(fn($item) => ['ar' => $item['d1_option'], 'en' => $item['d1_option_en']])
            ->unique()
            ->values();

        $d2OptionsWithTranslations = collect($options)
            ->map(fn($item) => ['ar' => $item['d2_option'], 'en' => $item['d2_option_en']])
            ->unique()
            ->values();

        $options_price_1 = $options_price_2 = $options_price_3 = $options_price_4 = [];
        $placeholder_2 = $placeholder_1 = isset($d1OptionsWithTranslations[0]) ? $d1OptionsWithTranslations[0]["ar"] : '';
        if (isset($d2OptionsWithTranslations[0]) && !empty($d2OptionsWithTranslations[0]["ar"])) {
            $placeholder_1 .= " - " . $d2OptionsWithTranslations[0]["ar"];
        }

        if (isset($d2OptionsWithTranslations[1]) && !empty($d2OptionsWithTranslations[1]["ar"])) {
            $placeholder_2 .= " - " . $d2OptionsWithTranslations[1]["ar"];
        } else {
            $placeholder_2 = '';
        }

        $placeholder_4 = $placeholder_3 = isset($d1OptionsWithTranslations[1]) ? $d1OptionsWithTranslations[1]["ar"] : '';
        if (isset($d2OptionsWithTranslations[0]) && !empty($d2OptionsWithTranslations[0]["ar"])) {
            $placeholder_3 .= " - " . $d2OptionsWithTranslations[0]["ar"];
        }
        if (isset($d2OptionsWithTranslations[1]) && !empty($d2OptionsWithTranslations[1]["ar"])) {
            $placeholder_4 .= " - " . $d2OptionsWithTranslations[1]["ar"];
        } else {
            $placeholder_4 = '';
        }

        $options_placeholders = [
            1 => $placeholder_1,
            2 => $placeholder_2,
            3 => $placeholder_3,
            4 => $placeholder_4
        ];

        if (count($options) == 2) {
            $options_price_1 = [
                $options[0]["price"],
                $options[0]["price_en"],
            ];
            $options_price_3 = [
                $options[1]["price"],
                $options[1]["price_en"],
            ];
        }

        if (count($options) == 4) {
            $options_price_1 = [
                $options[0]["price"],
                $options[0]["price_en"],
            ];
            $options_price_2 = [
                $options[1]["price"],
                $options[1]["price_en"],
            ];
            $options_price_3 = [
                $options[2]["price"],
                $options[2]["price_en"],
            ];
            $options_price_4 = [
                $options[3]["price"],
                $options[3]["price_en"],
            ];
        }

        $options_price = [
            1 => $options_price_1,
            2 => $options_price_2,
            3 => $options_price_3,
            4 => $options_price_4
        ];

        $type = $this->type;
        $prices = $data->itemPrices; // $data is the item being edited

        $options = [
            'd1_options' => $d1OptionsWithTranslations,
            'd2_options' => $d2OptionsWithTranslations,
        ];

        $analyticـaccounts = Setting::where('key', 'analyticـaccount')->get();

        if (!$data) {
            return redirect()->back();
        } else {
            return view('admin.items.edit', compact('data', 'categories', 'options_placeholders', 'options_price', 'options', 'type', 'prices', 'analyticـaccounts'));
        }
    }

    public function slider($type, Item $item)
    {
        $data = $item->getMedia(collectionName: "items_gallery");
        return view('admin.items.slider', compact('item', 'data', 'type'));
    }

    public function additional_info($type, Item $item)
    {
        // Load the additional information for the item, or create a new one if it doesn't exist.
        $additionalInfo = $item->additionalInfo ?? new AdditionalInfo();

        return view('admin.items.additional_info', compact('item', 'additionalInfo'));
    }

    public function update_additional_info(Request $request, $type, Item $item)
    {
        // Validation for incoming request
        $request->validate([
            'project_story' => 'nullable|string',
            'project_story_en' => 'nullable|string',
            'bold_description' => 'nullable|string',
            'bold_description_en' => 'nullable|string',
            'normal_description' => 'nullable|string',
            'normal_description_en' => 'nullable|string',
        ]);

        // Retrieve or create additional info for the item
        $additionalInfo = $item->additionalInfo ?? new AdditionalInfo();
        $additionalInfo->fill($request->all());
        $additionalInfo->item_id = $item->id; // Set the item_id
        $additionalInfo->save();
        SendItemToOdooJob::dispatch($additionalInfo->item_id)->delay(2);
        return redirect()->route('items.index', ["type" => $type])->with('success', __('app.updated successfully'));
    }

    public function seo($type, Item $item)
    {
        $seo = $item->seo ?? new Seo();
        return view('admin.items.seo', compact('item', 'seo'));
    }   

    public function update_seo(Request $request, $type, Item $item)
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

        $seo = $item->seo ?? new Seo();
        $seo->fill($request->all());
        $seo->model_id = $item->id;
        $seo->model_type = "App\Models\Item";
        $seo->save();

        if ($request->hasFile('image')) {
            $seo->clearMediaCollection('seo');
            $seo->addMedia($request->file('image'))->toMediaCollection('seo');
        }

        if ($request->hasFile('image_en')) {
            $seo->clearMediaCollection('seo_en');
            $seo->addMedia($request->file('image_en'))->toMediaCollection('seo_en');
        }
        return redirect()->route('items.index', ["type" => $type])->with('success', __('app.updated successfully'));
    }

    public function uploadSlider(Request $request)
    {
        $id = $request->item;
        $item = Item::find($id);
        // Validate the image input

        $request->validate([
            'image_item' => 'image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        if ($request->hasFile('image_item')) {
            $item->addMedia($request->file('image_item'))->toMediaCollection('items_gallery');
        }

        return redirect()->back()->with('success', __('app.updated successfully'));
    }

    public function clearImages(Request $request)
    {
        $id = $request->id;
        $item = Item::find($id);
        $data = $item->clearMediaCollection('items_gallery');

        if ($data) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }

    public function clearSingleImage(Request $request)
    {
        $id = $request->item_id;
        $item = Item::find($id);
        $data = $item->getMedia('items_gallery')->where('id', $request->id)->first();
        if ($data) {
            $data->delete();
        }
        if ($data) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $type, string $id)
    {
        // Find the item by ID
        $item = Item::findOrFail($id);
        // Define validation rules
        $rules = [
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => 'nullable|string',
            'description_en' => 'nullable|string',
            'beneficiaries_message' => 'nullable|string',
            'beneficiaries_message_en' => 'nullable|string',
            'description_after_payment' => 'nullable|string',
            'description_after_payment_en' => 'nullable|string',
            'location' => 'required|string|max:255',
            'location_en' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'payment_type' => 'sometimes|string|in:Both,One-Time,Subscription',
            'status' => 'sometimes|integer',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_tablet' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_mobile' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'price' => 'array',
            'price.*.value' => 'nullable|numeric|min:0',
            'price_en' => 'array',
            'price_en.*.value' => 'nullable|numeric|min:0',
            'is_default' => 'nullable|integer|min:1|max:4',

        ];

        if ($type != "organization") {
            $rules["amount"] = 'required|numeric|min:1';
            $rules["amount_usd"] = 'required|numeric|min:1';
            $rules["analyticـaccount"] = 'required|integer';
        }

        if ($type == "crowdfunding") {
            $rules["single_price"] = 'nullable|numeric|min:1';
            $rules["single_price_usd"] = 'nullable|numeric|min:1';
        }

	    if(!isset($validated["payment_type"])){
            $validated["payment_type"] = "Both";
        }
        // Custom messages for validation
        $messages = __('validation.items');

        // Validate the request
        $validated = $request->validate($rules, $messages);
        if (!isset($validated["status"])) {
            $validated["status"] = 0;
        }
        if ($type == "organization") {
            $validated["amount"] = 1;
            $validated["amount_usd"] = 1;
        }
        $validated["has_beneficiary"] = $type == "projects" ? $request->boolean('has_beneficiary') : false;
        PriceOption::where(["item_id" => $id])->delete();

        $isDefaultSelected = $request->input('is_default', null);

        if ($request->type == "projects") {
            $priceOptions = [
                [
                    "d1_option" => $request->dropdown_1_option_1,
                    "d1_option_en" => $request->dropdown_1_option_1_en,
                    "d2_option" => $request->dropdown_2_option_1,
                    "d2_option_en" => $request->dropdown_2_option_1_en,
                    "price" => $request->price_1_jod,
                    "price_en" => $request->price_1_usd,
                ],
                [
                    "d1_option" => $request->dropdown_1_option_1,
                    "d1_option_en" => $request->dropdown_1_option_1_en,
                    "d2_option" => $request->dropdown_2_option_2,
                    "d2_option_en" => $request->dropdown_2_option_2_en,
                    "price" => $request->price_2_jod,
                    "price_en" => $request->price_2_usd,
                ],
                [
                    "d1_option" => $request->dropdown_1_option_2,
                    "d1_option_en" => $request->dropdown_1_option_2_en,
                    "d2_option" => $request->dropdown_2_option_1,
                    "d2_option_en" => $request->dropdown_2_option_1_en,
                    "price" => $request->price_3_jod,
                    "price_en" => $request->price_3_usd,
                ],
                [
                    "d1_option" => $request->dropdown_1_option_2,
                    "d1_option_en" => $request->dropdown_1_option_2_en,
                    "d2_option" => $request->dropdown_2_option_2,
                    "d2_option_en" => $request->dropdown_2_option_2_en,
                    "price" => $request->price_4_jod,
                    "price_en" => $request->price_4_usd,
                ],
            ];

            foreach ($priceOptions as $index => $option) {
                if (!empty($option['price']) || !empty($option['price_en'])) {
                    $item->priceOptions()->create([
                        "d1_option" => $option['d1_option'],
                        "d1_option_en" => $option['d1_option_en'],
                        "d2_option" => $option['d2_option'],
                        "d2_option_en" => $option['d2_option_en'],
                        "price" => $option['price'],
                        "price_en" => $option['price_en'],
                        "is_default" => ($isDefaultSelected == ($index + 1)) ? 1 : 0, // Set as default if selected
                    ]);
                }
            }

            // If no default is selected, set the first option as default
            if (is_null($isDefaultSelected)) {
                $firstOption = $item->priceOptions()->first();
                if ($firstOption) {
                    $firstOption->update(['is_default' => 1]);
                }
            }
        }

        // Sync Arabic prices (price)
        $existingPrices = $item->itemPrices()->get();
        $submittedPrices = collect($validated['price'] ?? []);

        $submittedPrices->each(function ($price, $index) use ($item, $existingPrices) {
            $existing = $existingPrices->skip($index)->first();
            if ($existing) {
                $existing->update(['price' => $price['value']]); // Update existing price
            } elseif (!is_null($price['value'])) {
                $item->itemPrices()->create(['price' => $price['value']]); // Add new price
            }
        });

        // Remove extra Arabic prices
        $existingPrices->slice($submittedPrices->count())->each->delete();

        // Sync English prices (price_en)
        $existingPricesEn = $item->itemPrices()->get();
        $submittedPricesEn = collect($validated['price_en'] ?? []);

        $submittedPricesEn->each(function ($priceEn, $index) use ($item, $existingPricesEn) {
            $existing = $existingPricesEn->skip($index)->first();
            if ($existing) {
                $existing->update(['price_en' => $priceEn['value']]); // Update existing English price
            } elseif (!is_null($priceEn['value'])) {
                $item->itemPrices()->create(['price_en' => $priceEn['value']]); // Add new English price
            }
        });

        // Remove extra English prices
        $existingPricesEn->slice($submittedPricesEn->count())->each->delete();
        $validated["need_sync"] = 1;
        // Update the item with the validated data
        $item->update($validated);

        // Handle media (image updates) if files are provided
        if ($request->hasFile('image')) {
            $item->clearMediaCollection('items');
            $item->addMedia($request->file('image'))->toMediaCollection('items');
        }

        if ($request->hasFile('image_tablet')) {
            $item->clearMediaCollection('items_tablet');
            $item->addMedia($request->file('image_tablet'))->toMediaCollection('items_tablet');
        }

        if ($request->hasFile('image_mobile')) {
            $item->clearMediaCollection('items_mobile');
            $item->addMedia($request->file('image_mobile'))->toMediaCollection('items_mobile');
        }

        if ($request->hasFile('image_en')) {
            $item->clearMediaCollection('items_en');
            $item->addMedia($request->file('image_en'))->toMediaCollection('items_en');
        }
        SendItemToOdooJob::dispatch($item->id)->delay(2);
        return redirect()->route('items.index', ["type" => $type])->with('success', __('app.updated successfully'));

    }

    public function getPaidItems(string $type, string $itemId)
    {
        // Find the item by ID
        $item = Item::findOrFail($itemId);

        // Get all paid items for the selected item, along with the user info
        $uniquePaidItems = Cart::where('item_id', $itemId)
            ->where('is_paid', 1)
            ->with(['user', 'item', 'item.category', 'item.itemPrices']) // eager load user, item, and item prices
            ->get();

        $total = 0;
        foreach($uniquePaidItems as $cartItem){
            $total += $cartItem->price;
        }


        return view('admin.items.paid', compact('uniquePaidItems', 'item', 'type', 'total'));
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $type, Item $item, Request $request)
    {
        $data = $item->delete();

        if ($data) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }

    public function change_status($type, $id)
    {
        $item = Item::find($id);
        $item->status = !$item->status;
        $item->need_sync = 1;
        if($item->save()){
            SendItemToOdooJob::dispatch($item->id)->delay(2);
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
}
