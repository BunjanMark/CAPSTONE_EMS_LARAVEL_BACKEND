<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\AccountRole;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;

class AccountManagementController extends Controller
{
    //
    public function __construct(){
        $this->model = new AccountRole();
    }
    public function addProfile(Request $request, User $user){
        // add account profile
        // response
        try {

            // $createProfile = $this->model->create($request->all() + ['user_id' => $user->id]);
            // attach current user's ID
            $data = $request->all();
            $user_id = Auth::user()->id;
            $data['user_id'] = $user_id;
            if (!Auth::check()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            $createProfile = $this->model->create($data);
            return response()->json($createProfile, 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage()], 500);
        }
      
    }

    public function getProfile(User $user){
        // get account profile
        // response
        try {
            $getProfile = $this->model->where('user_id', Auth::user()->id)->get();
            return response()->json(["Message: " => "success", "User id" => Auth::user()->id, "data" => $getProfile], 200);

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request)
{
    $user = $request->user();

    $validatedData = $request->validate([
        'email' => 'required|email',
        'username' => 'required|string|max:255',
        'password' => 'nullable|string|min:6',
        'phone_number' => 'nullable|string|max:15',
    ]);

    if (!empty($validatedData['password'])) {
        $validatedData['password'] = bcrypt($validatedData['password']);
    }

    $user->update(array_filter($validatedData));
    return response()->json(['message' => 'Profile updated successfully']);
}


}
