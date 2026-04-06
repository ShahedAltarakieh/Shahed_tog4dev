<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\CollectionTeamMail;
use App\Mail\SendPasswordMail;
use App\Models\Cart;
use App\Models\CollectionTeam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Rules\ForbiddenNameKeyword;
use Str;

class CollectionTeamController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'first_name' => ['required', 'string', 'max:255', new ForbiddenNameKeyword(app()->getLocale())],
            'last_name' => ['required', 'string', 'max:255', new ForbiddenNameKeyword(app()->getLocale())],
            'email' => 'required|email',
            'phone' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $temp_id = $request->temp_id ?? null;

        // Check if the user already exists
        $user = User::where('email', $validatedData['email'])->first();

        if (!$user) {
            // Generate a random password
            $password = Str::random(10);

            // Create the user account with the provided data
            $user = User::create([
                'first_name' => $validatedData["first_name"],
                'last_name' => $validatedData["last_name"],
                'email' => $validatedData['email'],
                'password' => Hash::make($password), // Hash the password
                'role' => 2, // Assign role to collection team
                'phone' => $validatedData['phone'],
                'country' => "Jordan",
                'city' => $validatedData['city'],
            ]);

            // Send the password to the user's email
            Mail::to($validatedData['email'])->send(new SendPasswordMail($user, $password));
        }

        // Create the new CollectionTeam record and associate with the user
        $collectionTeam = CollectionTeam::create([
            'user_id' => $user->id, // Use the user ID (whether new or existing)
            'email' => $validatedData['email'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'phone' => $validatedData['phone'],
            'country' => "Jordan",
            'city' => $validatedData['city'],
            'address' => $validatedData['address'],
        ]);

        Cart::where(fn($query) => $query
            ->where('temp_id', $temp_id)
            ->orWhere('user_id', $user->id))
            ->where('is_paid', 0)
            ->update([
                'is_paid' => 2,
                'collection_team_id' => $collectionTeam->id
        ]);

        $carts = Cart::where(fn($query) => $query
                ->where('temp_id', $temp_id)
                ->orWhere('user_id', $user->id))
                ->where('is_paid', 2)
                ->where('collection_team_id', $collectionTeam->id)
                ->with('model') // Load related model data
                ->get();

        Mail::to($user->email)->cc(env('BILLS_EMAIL'))->send(
            new CollectionTeamMail($carts)
        );

        return response()->json([
            'message' => 'Collection team member created successfully.',
            'data' => $collectionTeam,
        ], 201);
    }

}
