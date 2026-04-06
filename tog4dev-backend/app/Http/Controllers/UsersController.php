<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function fetch_data(Request $request)
    {
        $query = User::query();

        // Search
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('organization_name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            });
        }

        // Columns for ordering
        $columns = [
            'name',
            'email',
            'organization_name',
            'city',
            'birthday',
            'country',
            'created_at',
            'action',
        ];

        if ($request->has('order.0')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderDir = $request->input('order.0.dir');
            $columnName = $columns[$orderColumnIndex] ?? 'created_at';

            if ($columnName === 'name') {
                $query->orderBy('first_name', $orderDir)->orderBy('last_name', $orderDir);
            } else {
                $query->orderBy($columnName, $orderDir);
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $total = User::count();
        $filtered = (clone $query)->count();

        $data = $query
            ->skip($request->input('start'))
            ->take($request->input('length'))
            ->get();

        $formatted = $data->map(function ($user) {
            $editUrl = route('user.edit', $user->id);
            $paymentsUrl = route('users.payments', $user->id);

            $editButton = '<a href="' . $editUrl . '" class="btn btn-secondary"
                               data-toggle="tooltip" data-placement="top"
                               title="' . e(__('app.edit')) . '"
                               data-id="' . $user->id . '">
                               <i class="fas fa-edit"></i>
                           </a>';

            $paymentsButton = '<a class="btn btn-primary" href="' . $paymentsUrl . '">
                                   <i class="mdi mdi-currency-usd"></i>
                               </a>';

            return [
                'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')),
                'email' => $user->email,
                'organization_name' => $user->organization_name ?? __('app.not_available'),
                'city' => $user->city ?? __('app.not_available'),
                'birthday' => $user->birthday ? $user->birthday->format('Y-m-d') : __('app.not_available'),
                'country' => $user->country ?? __('app.not_available'),
                'created_at' => $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '',
                'action' => $editButton . ' ' . $paymentsButton,
            ];
        });

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $formatted,
        ]);
    }

    public function edit(User $user, string $id)
    {
        $data = User::find($id);
        if (!$data) {
            return redirect()->back();
        } else {
            return view('admin.users.edit', compact('data'));
        }
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'organization_name' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'country' => 'nullable|string|max:255',
            'password' => 'nullable|min:8',
        ]);

        // Find the user to update
        $user = User::findOrFail($id);

        // Update user details
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->email = $validated['email'];
        $user->organization_name = $validated['organization_name'];
        $user->city = $validated['city'];
        $user->country = $validated['country'];
        // Handle birthday as a date format
        if ($validated['birthday']) {
            $user->birthday = $validated['birthday'];
        }

        // If password is provided, hash it and update it
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        // Save the updated user data
        $user->save();

        // Return success response
        return redirect()->route('user.edit', $user->id)->with('success', __('app.updated successfully'));
    }

    public function showPayments($id)
    {
        // Retrieve the selected user with their payments
        $payments = Payment::with('cartItems', 'influencer')
        ->where('user_id', $id)->where('status', 'approved')->get();
        // Pass the user and their payments to the view
        return view('admin.users.payments', compact('payments'));
    }
}
