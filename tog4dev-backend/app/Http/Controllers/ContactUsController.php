<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
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
    public function index($type)
    {
        // Initialize query based on the type
        switch ($type) {
            case 'organization':
                $results = ContactUs::getOrganization()->where('is_read', 0);
                break;
            case 'projects':
                $results = ContactUs::getProjects()->where('is_read', 0);
                break;
            default:
                $results = ContactUs::query(); // Default: Fetch all contacts if type is unrecognized
                break;
        }

        // Paginate the results
        $data = $results->orderBy('created_at', 'desc')->get();

        // Pass the type and contacts to the view
        return view('admin.contact_us.index', compact('data', 'type'));
    }

    public function showRead($type)
    {
        // Initialize query based on the type
        switch ($type) {
            case 'organization':
                $results = ContactUs::getOrganization()->where('is_read', 1);
                break;
            case 'projects':
                $results = ContactUs::getProjects()->where('is_read', 1);
                break;
            default:
                $results = ContactUs::query(); // Default: Fetch all contacts if type is unrecognized
                break;
        }

        // Paginate the results
        $data = $results->orderBy('created_at', 'desc')->get();

        // Pass the type and contacts to the view
        return view('admin.contact_us.index', compact('data', 'type'));
    }

    /**
     * Show the details of a specific contact.
     */
    public function show($type, $id)
    {
        $contact = ContactUs::findOrFail($id); // Find contact by ID or throw 404 if not found

        // Return the show view with the contact and type
        return view('admin.contact_us.show', compact('contact', 'type'));
    }

    /**
     * Mark a contact message as read.
     */
    public function markAsRead(string $type, $id, Request $request)
    {
        $contact = ContactUs::findOrFail($id);
        $contact->update(['is_read' => !$contact->is_read]);
        if ($contact) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }

    /**
     * Delete a contact message.
     */
    public function destroy(string $type, ContactUs $contact_us, Request $request)
    {
        $contact_us->delete();
        if ($contact_us) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
}
