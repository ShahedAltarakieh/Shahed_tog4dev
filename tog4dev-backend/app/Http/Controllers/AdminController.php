<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereIn('role', [0,1])->get();

        return view('admin.admin.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'role' => 'required',
            'password' => 'required|min:8',
            'email' => 'required|email|max:255',
        ]);

        $user = User::where("email", $request->email)->where('role', 2)->first();
        if($user){
            $user->username = $request->username;
            $user->role = $request->role;
            $user->admin_password = Hash::make($request->password);
            $user->save();
        } else {
            $user = new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->admin_password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->back()->with('success', 'تم الإضافة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, string $id)
    {
        $data = User::find($id);
        if (!$data) {
            return redirect()->back();
        } else {
            return view('admin.admin.edit', compact('data'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role' => 'required',
            'password' => 'nullable|min:8',
        ]);

        $data = User::find($id);

        $data->username = $request->username;
        $data->email = $request->email;
        $data->role = $request->role;
        if (!empty($request->password)) {
            $data->admin_password = Hash::make($request->password);
        }
        $data->save();
        return redirect()->back()->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Request $request)
    {
        $id = $request->id;
        if($id == 1){
            echo json_encode(array("status" => "failure"));
        }
        $data = User::find($id);
        $data->admin_password = null;
        $data->username = null;
        $data->role = 2;
        $data->save();
        if ($data) {
            echo json_encode(array("status" => "success"));
        } else {
            echo json_encode(array("status" => "failure"));
        }
    }
}
