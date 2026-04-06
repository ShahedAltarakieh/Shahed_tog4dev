<?php

namespace App\Http\Controllers;

use App\Mail\PaymentReceiptMail;
use App\Models\Cart;
use App\Models\CollectionTeam;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;
use Str;
use App\Exports\CollectionTeamExport;
use Maatwebsite\Excel\Facades\Excel;

class CollectionTeamController extends Controller
{
    public function index()
    {
        $collectionTeams = CollectionTeam::all(); // Retrieve all collection teams
        return view('admin.collection_team.index', compact('collectionTeams'));
    }

    public function edit(CollectionTeam $collectionTeam)
    {
        return view('admin.collection_team.edit', compact('collectionTeam'));
    }

    public function update(Request $request, CollectionTeam $collectionTeam)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:collection_team,email,' . $collectionTeam->id,
            'phone' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        // Update the collection team data
        $collectionTeam->update([
            'full_name' => $validatedData['full_name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'country' => $validatedData['country'],
            'city' => $validatedData['city'],
            'address' => $validatedData['address'],
            'status' => $validatedData['status'] ?? 0, // Default to 0 if not provided
        ]);

        // Return success response
        return redirect()->route('collection_team.index')->with('success', __('app.collection team updated successfully.'));
    }

    public function show(CollectionTeam $collectionTeam)
    {
        $allItemsPaid = $collectionTeam->cartItems->every(fn($item) => $item->is_paid === 1);

        return view('admin.collection_team.show', ['collectionTeam' => $collectionTeam, 'allItemsPaid' => $allItemsPaid,]);
    }

    public function destroy($id)
    {
        // Find the collection team by ID
        $collectionTeam = CollectionTeam::find($id);

        // Check if the collection team exists
        if (!$collectionTeam) {
            return response()->json([
                'message' => 'Collection team not found.'
            ], 404);
        }

        // Soft delete the collection team
        $collectionTeam->delete();

        if ($collectionTeam) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }

    public function confirmPayment(Request $request, $collectionTeamId)
    {

        $collectionTeam = CollectionTeam::find($collectionTeamId);

        // Fetch all unpaid items for the authenticated user
        $cartItems = Cart::where('collection_team_id', $collectionTeamId)
        ->where('is_paid', 2)
        ->get();

        $cart_amount = 0;

        foreach ($cartItems as $cartItem) {
            $cart_amount += $cartItem->price;
            $items[] = [
                'item_id' => $cartItem->item_id,
                'price' => $cartItem->price,
                'type' => $cartItem->type,
            ];
        }

        $cart_id = 'CT' . time();

        $payment = Payment::create([
            'user_id' => $collectionTeam->user_id,
            'cart_id' => $cart_id,
            'status' => 'approved',
            'amount' => $cart_amount,
            'collection_team_id' => $collectionTeamId,
            'payment_type' => 'collection',
            'acquirer_message' => null,
            'acquirer_rrn' => null,
            'resp_code' => null,
            'resp_message' => null,
            'signature' => null,
            'token' => null,
            'tran_ref' => null,
            'lang' => 'ar',
            'send_email' => 1
        ]);

        // Update all unpaid cart items for the collection team to mark as paid
        Cart::where('collection_team_id', $collectionTeamId)
            ->where('is_paid', 2)
            ->update(['is_paid' => 1, 'payment_id' => $payment->id]);

        return redirect()->back()->with('success', 'Payment confirmed successfully!');
    }

    public function downloadExcel()
    {
        return Excel::download(new CollectionTeamExport(), 'collection-team.xlsx');        
    }
}
