<?php

namespace App\Http\Controllers;

use App\Models\QuickContribution;
use App\Models\Category;
use App\Models\Setting;
use App\Models\QuickContributionPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\OdooJobs\SendQuickContributionToOdooJob;

class QuickContributionController extends Controller
{
    // Display a listing of the contributions
    public function index()
    {
        $quickContributions = QuickContribution::with('category')->get();
        return view('admin.quick_contributions.index', compact('quickContributions'));
    }

    // Show the form for creating a new contribution
    public function create()
    {
        $categories = Category::all(); // You can adjust this based on your app structure
        $analyticـaccounts = Setting::where('key', 'analyticـaccount')->get();
        return view('admin.quick_contributions.create', compact('categories', 'analyticـaccounts'));
    }

    // Store a newly created contribution
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => 'required|string',
            'description_en' => 'required|string',
            'beneficiaries_message' => 'nullable|string',
            'beneficiaries_message_en' => 'nullable|string',
            'description_after_payment' => 'nullable|string',
            'description_after_payment_en' => 'nullable|string',
            'location' => 'required|string|max:255',
            'location_en' => 'required|string|max:255',
            'type_id' => 'required|integer',
            'analyticـaccount' => 'required|integer',
            'category_id' => 'nullable|integer',
            'target' => 'nullable|integer',
            'target_usd' => 'nullable|integer',
            'price.*' => 'nullable|numeric',
            'price_en.*' => 'nullable|numeric',
            'status' => 'nullable|boolean',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_tablet' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_mobile' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'single_price' => 'nullable|numeric|min:1',
            'single_price_usd' => 'nullable|numeric|min:1',
        ]);

        try {
            DB::beginTransaction();
        // Create the QuickContribution record
        $quickContribution = QuickContribution::create([
            'title' => $validatedData['title'],
            'title_en' => $validatedData['title_en'],
            'description' => $validatedData['description'],
            'description_en' => $validatedData['description_en'],
            'beneficiaries_message' => $validatedData['beneficiaries_message'],
            'beneficiaries_message_en' => $validatedData['beneficiaries_message_en'],
            'description_after_payment' => $validatedData['description_after_payment'],
            'description_after_payment_en' => $validatedData['description_after_payment_en'],
            'location' => $validatedData['location'],
            'location_en' => $validatedData['location_en'],
            'target' => $validatedData['target'],
            'analyticـaccount' => $validatedData["analyticـaccount"],
            'target_usd' => $validatedData['target_usd'],
            'type_id' => $validatedData['type_id'],
            'category_id' => $validatedData['category_id'],
            'status' => $request->has('status') ? 1 : 0,
            'single_price' => $validatedData['single_price'],
            'single_price_usd' => $validatedData['single_price_usd'],
            'need_sync' => 1
        ]);

            // Save prices into the QuickContributionPrice table
            foreach ($validatedData['price'] as $index => $price) {
                if ($price || $validatedData['price_en'][$index]) {
                    QuickContributionPrice::create([
                        'quick_contribution_id' => $quickContribution->id,
                        'price' => $price,
                        'price_usd' => $validatedData['price_en'][$index] ?? null,
                    ]);
                }
            }

            $quickContribution->addMedia($request->file('image'))->toMediaCollection('quick_contribute');

            if ($request->hasFile('image_tablet')) {
                $quickContribution->addMedia($request->file('image_tablet'))->toMediaCollection('quick_contribute_tablet');
            }

            if ($request->hasFile('image_mobile')) {
                $quickContribution->addMedia($request->file('image_mobile'))->toMediaCollection('quick_contribute_mobile');
            }

            if ($request->hasFile('image_en')) {
                $quickContribution->addMedia($request->file('image_en'))->toMediaCollection('quick_contribute_en');
            }

            DB::commit();
            SendQuickContributionToOdooJob::dispatch($quickContribution->id)->delay(2);
            if(isset($request->save_and_return) && !empty($request->save_and_return)){
                return redirect()->route('quick-contributions.index')->with('success', __('app.add successfully'));
            } else{
                return redirect()->back()->with('success', __('app.add successfully'));
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Show the form for editing the specified contribution
    public function edit(QuickContribution $quickContribution)
    {
        $categories = Category::all();
        $analyticـaccounts = Setting::where('key', 'analyticـaccount')->get();
        return view('admin.quick_contributions.edit', compact('quickContribution', 'categories', 'analyticـaccounts'));
    }

    // Update the specified contribution
    public function update(Request $request, $id)
    {
        $quickContribution = QuickContribution::findOrFail($id);

        // Validate the incoming request
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description' => 'required|string',
            'description_en' => 'required|string',
            'beneficiaries_message' => 'nullable|string',
            'beneficiaries_message_en' => 'nullable|string',
            'description_after_payment' => 'nullable|string',
            'description_after_payment_en' => 'nullable|string',
            'location' => 'required|string|max:255',
            'location_en' => 'required|string|max:255',
            'type_id' => 'required|integer',
            'category_id' => 'nullable|integer',
            'target' => 'nullable|integer',
            'analyticـaccount' => 'required|integer',
            'target_usd' => 'nullable|integer',
            'prices.*.price' => 'nullable|numeric',
            'prices.*.price_usd' => 'nullable|numeric',
            'status' => 'nullable|boolean',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_tablet' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_mobile' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_en' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'single_price' => 'nullable|numeric|min:1',
            'single_price_usd' => 'nullable|numeric|min:1',
        ]);

        // Update Quick Contribution details
        $quickContribution->update([
            'title' => $validatedData['title'],
            'title_en' => $validatedData['title_en'],
            'description' => $validatedData['description'],
            'description_en' => $validatedData['description_en'],
            'beneficiaries_message' => $validatedData['beneficiaries_message'],
            'beneficiaries_message_en' => $validatedData['beneficiaries_message_en'],
            'description_after_payment' => $validatedData['description_after_payment'],
            'description_after_payment_en' => $validatedData['description_after_payment_en'],
            'location' => $validatedData['location'],
            'location_en' => $validatedData['location_en'],
            'analyticـaccount' => $validatedData["analyticـaccount"],
            'target' => $validatedData['target'],
            'target_usd' => $validatedData['target_usd'],
            'type_id' => $validatedData['type_id'],
            'category_id' => $validatedData['category_id'],
            'status' => $request->has('status') ? 1 : 0,
            'single_price' => $validatedData['single_price'],
            'single_price_usd' => $validatedData['single_price_usd'],
            'need_sync' => 1
        ]);

        // Update Prices
        foreach ($validatedData['prices'] ?? [] as $priceId => $priceData) {
            $price = QuickContributionPrice::find($priceId);
            if ($price) {
                $price->update([
                    'price' => $priceData['price'] ?? null,
                    'price_usd' => $priceData['price_usd'] ?? null,
                ]);
            }
        }

        if ($request->hasFile('image')) {
            $quickContribution->clearMediaCollection('quick_contribute');
            $quickContribution->addMedia($request->file('image'))->toMediaCollection('quick_contribute');
        }

        if ($request->hasFile('image_tablet')) {
            $quickContribution->clearMediaCollection('quick_contribute_tablet');
            $quickContribution->addMedia($request->file('image_tablet'))->toMediaCollection('quick_contribute_tablet');
        }

        if ($request->hasFile('image_mobile')) {
            $quickContribution->clearMediaCollection('quick_contribute_mobile');
            $quickContribution->addMedia($request->file('image_mobile'))->toMediaCollection('quick_contribute_mobile');
        }

        if ($request->hasFile('image_en')) {
            $quickContribution->clearMediaCollection('quick_contribute_en');
            $quickContribution->addMedia($request->file('image_en'))->toMediaCollection('quick_contribute_en');
        }
        SendQuickContributionToOdooJob::dispatch($quickContribution->id)->delay(2);
        return redirect()->route('quick-contributions.index')->with('success', __('app.updated successfully'));
    }

    // Remove the specified contribution from storage
    public function destroy(QuickContribution $quickContribution)
    {
        $quickContribution->delete();
        if ($quickContribution) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }

    public function change_status($id)
    {
        $item = QuickContribution::find($id);
        $item->status = !$item->status;
        $item->need_sync = 1;
        if($item->save()){
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
}
