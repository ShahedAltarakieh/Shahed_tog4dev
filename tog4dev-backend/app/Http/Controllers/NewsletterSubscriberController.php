<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterSubscriberController extends Controller
{
    /**
     * Display a listing of the newsletter subscribers.
     */
    public function index()
    {
        $data = NewsletterSubscriber::all();
        return view('admin.newsletter_subscribers.index', compact('data'));
    }

    /**
     * Update the status of a subscriber.
     */
    public function updateStatus(Request $request, NewsletterSubscriber $subscriber)
    {
        $validated = $request->validate([
            'status' => 'required|integer',
        ]);

        $subscriber->update(['status' => $validated['status']]);

        return response()->json(['status' => 'success', 'message' => 'Status updated successfully']);
    }

    /**
     * Delete a subscriber.
     */
    public function destroy(NewsletterSubscriber $subscriber, Request $request)
    {
        $subscriber->delete();
        if ($subscriber) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
}
