<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\NewsletterSubscriberResource;

class NewsletterSubscriberController extends Controller
{
    /**
     * Store a newly created newsletter subscriber in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'email' => 'required|email|unique:newsletter_subscribers,email',
            ]);

            // Create a new subscriber with status always active
            $subscriber = NewsletterSubscriber::create([
                'email' => $validated['email'],
                'status' => 1, // Always active
            ]);

            // Return the created subscriber response using resource
            return response()->json([
                'message' => 'Newsletter subscriber added successfully.',
                'data' => new NewsletterSubscriberResource($subscriber),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while adding the subscriber.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
