<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Str;
use App\Rules\ForbiddenNameKeyword;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;

        if ($request->temp_id) {
            Cart::where("temp_id", $request->temp_id)->where('is_paid', 0)->update(["user_id" => $user->id]);
        }

        $cart = Cart::where("user_id", $user->id)->where('is_paid', 0)->count();

        return response()->json([
            'user' => $user,
            'token' => $token,
            "cart" => $cart
        ]);
    }

    public function register(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255', new ForbiddenNameKeyword()],
            'last_name' => ['required', 'string', 'max:255', new ForbiddenNameKeyword()],
            'phone' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'organization_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'birthday' => 'required|date',
            'country' => 'required|string|max:255',
        ]);

        // Return validation errors if validation fails
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 400);
        }

        // Create a new user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'organization_name' => $request->organization_name,
            'city' => $request->city,
            'birthday' => $request->birthday,
            'country' => $request->country,
            'role' => 2, // Default role, for example: '2' means regular user
        ]);

        // Create a new API token for the user
        $token = $user->createToken('API Token')->plainTextToken;

        // Return the response with the user details and the generated token
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $token = Str::random(64);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => bcrypt($token),
                'created_at' => now(),
            ]
        );

        if(app()->getLocale() == "ar"){
            $resetLink = 'https://tog4dev.com/ar/استعادة-كلمة-المرور/' . $token . '?email=' . urlencode($user->email);
        } else {
            $resetLink = 'https://tog4dev.com/en/reset-password/' . $token . '?email=' . urlencode($user->email);
        }

        Mail::to($user->email)->send(new PasswordResetMail($resetLink, $user));

        return response()->json([
            // 'reset_link' => $resetLink,
            'message' => 'Reset link generated and email sent successfully.',
        ]);
    }

    public function resetPassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Return validation errors if validation fails
        if ($validator->fails()) {
            return response()->json(['message' => 'Validation error', 'errors' => $validator->errors()], 400);
        }

        $record = DB::table('password_resets')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return response()->json(['message' => 'Invalid token or email.'], 400);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            DB::table('password_resets')->where('email', $request->email)->delete();

            return response()->json(['message' => 'Password has been successfully reset'], 200);
        }

        return response()->json(['message' => 'User not found.'], 404);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|confirmed|min:8',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully.',
        ], 200);
    }
    
    public function logout(Request $request)
    {
        // Revoke the token used for this request
        if (auth('sanctum')->user()->currentAccessToken()) {
            auth('sanctum')->user()->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
}
