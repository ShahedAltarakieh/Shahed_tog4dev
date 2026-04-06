<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\ContactUsResource;

class ContactUsController extends Controller
{
    /**
     * Store a newly created Contact Us entry in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate incoming data
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'organization_name' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'message' => 'required|string',
                'type' => 'required|integer|in:1,2,3',
            ]);

            // Create a new Contact Us entry with default values for status and is_read
            $contact = ContactUs::create(array_merge($validated, [
                'status' => 1, // Default status
                'is_read' => 0, // Default is_read
            ]));

            // Return success response using ContactUsResource
            return response()->json([
                'message' => 'Contact Us entry created successfully.',
                'data' => new ContactUsResource($contact),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the Contact Us entry.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
