<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\Api\V2\UserResource;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Str;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function index(Request $request)
    {
        try {
            $perPage = $request->query('per-page', 50);
            $orderBy = $request->query('order', 'DESC');

            $query = User::whereNull('deleted_at');
            $query->orderBy('id', strtoupper($orderBy) === 'DESC' ? 'DESC' : 'ASC');

            $users = $query->paginate($perPage);

            return UserResource::collection($users);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching users.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get a single User.
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => new UserResource($user),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User not found.',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Store a new user.
     */
    public function store(Request $request)
    {
        try {
            $this->logRequest($request->all());
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'nullable|string|min:8',
                'organization_name' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'birthday' => 'required|date',
                'country' => 'required|string|max:255',
                'created_from' => 'nullable|string|max:255',
                'partnerId' => 'nullable|integer',
                'x-source-type' => 'nullable|string',
            ]);

            if(!isset($validatedData["password"]) || empty($validatedData["password"])){
                $validatedData["password"] = Str::random(10);
            }
            $validatedData["role"] = 2;
            $validatedData["password"] = Hash::make($validatedData["password"]);
            
            $validatedData["source"] = $validatedData["x-source-type"] ?? null;
            $validatedData["need_sync"] = $validatedData["x-source-type"] == "odoo" ? 0 : 1;

            if($validatedData["partnerId"]){
                $validatedData["odoo_id"] = $validatedData["partnerId"];
            }
            
            $user = User::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully.',
                'data' => new UserResource($user),
            ], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            $this->logError($request->all(), $e);
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            $this->logError($request->all(), $e);
            return response()->json([
                'message' => 'Error creating user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'organization_name' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'birthday' => 'required|date',
                'country' => 'required|string|max:255'
            ]);

            if($request->password != ''){
                $validatedData["password"] = 'required|string|min:8';
                $validatedData["password"] = Hash::make($validatedData["password"]);
            }

            
            $user->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully.',
                'data' => new UserResource($user),
            ]);
        } catch (ValidationException $e) {
            $this->logError($request->all(), $e);
            return response()->json([
                'message' => 'Validation error.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            $this->logError($request->all(), $e);
            return response()->json([
                'message' => 'Error updating user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a User.
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'message' => 'User not found.',
                ], 404);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    protected function logRequest($payload)
    {
        Log::channel('odoo')->info("ODDO API Users ", [
            'payload'  => $payload
        ]);
    }

    protected function logError($payload, $error)
    {
        Log::channel('odoo')->error("Odoo API Users ", [
            'payload' => $payload,
            'error'   => $error instanceof \Throwable ? $error->getMessage() : $error,
        ]);
    }
}
