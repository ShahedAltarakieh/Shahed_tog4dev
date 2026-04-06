<?php

namespace App\Http\Controllers;

use App\Models\Influencer;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InfluencerController extends Controller
{
    public function index()
    {
        return view('admin.influencers.index');
    }

    public function fetch_data(Request $request)
    {
        $query = Influencer::withCount('visits');

        // Search
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Column mapping for ordering
        $columns = [
            'id',
            'name',
            'code',
            'expiry_date',
            'visits_count',
            'action',
        ];

        if ($request->has('order.0')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columnName = $columns[$orderColumnIndex] ?? 'id';

            if ($columnName === 'visits_count') {
                $query->orderBy('visits_count', $orderDir);
            } elseif ($columnName === 'expiry_date') {
                $query->orderBy('expiry_date', $orderDir);
            } else {
                $query->orderBy($columnName, $orderDir);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $total = Influencer::count();
        $filtered = (clone $query)->count();

        $data = $query
            ->skip($request->input('start'))
            ->take($request->input('length'))
            ->get();

        $formatted = $data->map(function ($influencer) {
            $editUrl = route('influencers.edit', $influencer->id);
            $paymentsUrl = route('influencer.payments', $influencer->id);

            $copyButton = '<button type="button" class="btn btn-outline-secondary btn-copy"
                                data-clipboard-text="' . e($influencer->page_link) . '"
                                title="' . e(__('app.copy_page_link')) . '"
                                aria-label="' . e(__('app.copy_page_link')) . '">
                                <i class="fas fa-copy"></i>
                            </button>';

            $editButton = '<a href="' . $editUrl . '"
                               class="btn btn-secondary"
                               data-toggle="tooltip"
                               data-placement="top"
                               title="' . e(__('app.edit')) . '"
                               data-id="' . $influencer->id . '">
                               <i class="fas fa-edit"></i>
                           </a>';

            $paymentsButton = '<a class="btn btn-primary" href="' . $paymentsUrl . '">
                                   <i class="mdi mdi-currency-usd"></i>
                               </a>';

            $deleteButton = '<button class="btn btn-danger btn-delete"
                                     data-toggle="tooltip"
                                     data-placement="top"
                                     title="' . e(__('app.delete')) . '"
                                     data-table="influencers"
                                     data-id="' . $influencer->id . '">
                                     <i class="fas fa-trash-alt"></i>
                              </button>';

            return [
                'id' => $influencer->id,
                'name' => $influencer->name,
                'code' => $influencer->code,
                'expiry_date' => $influencer->expiry_date
                    ? $influencer->expiry_date->format('d/m/Y')
                    : __('app.no_expiry'),
                'visits_count' => $influencer->visits_count,
                'action' => $copyButton . ' ' . $editButton . ' ' . $paymentsButton . ' ' . $deleteButton,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $formatted,
        ]);
    }

    // Show the form for creating a new resource
    public function create()
    {
        return view('admin.influencers.create');
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:influencers,code',
            'page_link' => 'required|url',
            'expiry_date' => 'nullable|date|after_or_equal:today',
        ]);

        $pageLink = $validatedData['page_link'] . '?t4d=' . urlencode($validatedData['code']);

        Influencer::create([
            'name' => $validatedData['name'],
            'code' => $validatedData['code'],
            'page_link' => $pageLink,
            'expiry_date' => $validatedData['expiry_date'] ?? null,
        ]);

        if ($request->has('save_and_return')) {
            return redirect()->route('influencers.index')->with('success', __('app.add successfully'));
        } else {
            return redirect()->back()->with('success', __('app.add successfully'));
        }
    }

    // Display the specified resource
    public function show(Influencer $influencer)
    {
        // $influencer->referredUsers() is the relationship returning a collection of users
        $referredUsers = $influencer->referredUsers()->get();

        return view('admin.influencers.show', compact('influencer', 'referredUsers'));
    }


    // Show the form for editing the specified resource
    public function edit(Influencer $influencer)
    {
        // Remove the 'T4D' parameter from the page_link
        $cleanPageLink = $this->removeParameterFromUrl($influencer->page_link, 't4d');

        return view('admin.influencers.edit', compact('influencer', 'cleanPageLink'));
    }

    // Update the specified resource in storage
    public function update(Request $request, Influencer $influencer)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:influencers,code,' . $influencer->id,
            'page_link' => 'required|url|unique:influencers,page_link,' . $influencer->id,
            'expiry_date' => 'nullable|date|after_or_equal:today',
        ]);

        $pageLink = $validatedData['page_link'] . '?t4d=' . urlencode($validatedData['code']);


        // Update the Influencer record
        $influencer->update([
            'name' => $validatedData['name'],
            'code' => $validatedData['code'],
            'page_link' => $pageLink,
            'expiry_date' => $validatedData['expiry_date'] ?? null,
        ]);

        return redirect()->route('influencers.index')->with('success', __('app.updated successfully'));
    }

    // Remove the specified resource from storage
    public function destroy(Influencer $influencer)
    {
        $influencer->delete();
        // Return JSON response similar to the QuickContributionController
        if ($influencer) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "failure"]);
        }
    }

    private function removeParameterFromUrl($url, $param)
    {
        // Parse the URL into components
        $parsedUrl = parse_url($url);

        // If there's no query, return the original URL
        if (!isset($parsedUrl['query'])) {
            return $url;
        }

        // Parse the query string into an associative array
        parse_str($parsedUrl['query'], $queryParams);

        // Remove the specified parameter
        unset($queryParams[$param]);

        // Rebuild the query string
        $newQuery = http_build_query($queryParams);

        // Reconstruct the URL without the removed parameter
        $newUrl = $this->buildUrl($parsedUrl, $newQuery);

        return $newUrl;
    }

    private function buildUrl($parsedUrl, $newQuery)
    {
        // Start building the new URL
        $newUrl = '';

        // Add the scheme (e.g., http, https) if present
        if (isset($parsedUrl['scheme'])) {
            $newUrl .= $parsedUrl['scheme'] . '://';
        }

        // Add the host if present
        if (isset($parsedUrl['host'])) {
            $newUrl .= $parsedUrl['host'];
        }

        // Add the port if present
        if (isset($parsedUrl['port'])) {
            $newUrl .= ':' . $parsedUrl['port'];
        }

        // Add the path if present
        if (isset($parsedUrl['path'])) {
            $newUrl .= $parsedUrl['path'];
        }

        // Add the new query string if present
        if ($newQuery) {
            $newUrl .= '?' . $newQuery;
        }

        // Add the fragment if present
        if (isset($parsedUrl['fragment'])) {
            $newUrl .= '#' . $parsedUrl['fragment'];
        }

        return $newUrl;
    }

    public function showPayments($id)
    {
        // Retrieve payments for the given influencer
        $payments = Payment::where('referrer_id', $id)
            ->where('status', 'approved')
            // Eager-load the cartItems so we're not hitting the database repeatedly
            ->with('cartItems')
            ->get();
    
        // Sum of ALL payments (just sums Payment::amount)
        $totalAmount = $payments->sum('amount');
    
        // Now collect all cart items across these payments
        // flatMap() merges all cart items into a single collection
        $allCartItems = $payments->flatMap->cartItems;
    
        // If your Cart model has 'type' = 'one_time' or 'monthly'
        $oneTimeCartItems = $allCartItems->where('type', 'one_time');
        $subscriptionCartItems = $allCartItems->where('type', 'monthly');
        // If the cart items store the item cost in `price` (and you might multiply by quantity)
        $oneTimeTotal = $oneTimeCartItems->sum(fn($item) => $item->price);
        $subscriptionTotal = $subscriptionCartItems->sum(fn($item) => $item->price );

        $influencer = Influencer::find($id);
    
        return view('admin.influencers.payments', [
            'payments'          => $payments,
            'totalAmount'       => $totalAmount,
            'oneTimeTotal'      => $oneTimeTotal,
            'subscriptionTotal' => $subscriptionTotal,
            'influencer'        => $influencer,
        ]);
    }
    
}
