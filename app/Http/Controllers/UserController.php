<?php

namespace App\Http\Controllers;

use App\Models\PendingUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Import the Hash facade

class UserController extends Controller
{

    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);
    
            $trimmedPassword = trim($request->password);
            $user = User::where('username', $request->username)->first();

            if (!$user) {
                \Log::error('User not found', ['username' => $request->username]);
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
    
            if (!$user || !Hash::check($trimmedPassword, $user->password)) {
                \Log::warning('Invalid login credentials', ['username' => $request->username]);
                return response()->json(['error' => 'The provided credentials are incorrect.'], 401);
            }
    
            // Authentication successful, return user data or token
            return response()->json(['message' => 'Login successful', 'user' => $user], 200);
    
        } catch (\Exception $e) {
            \Log::error('Login error', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Server error, please try again later.'], 500);
        }
    }    
    
    


    // Accept a pending user and transfer them to the user table
    public function acceptPendingUser($id)
{
    try {
        $pendingUser = PendingUser::find($id);

        if (!$pendingUser) {
            return response()->json(['error' => 'Pending user not found'], 404);
        }

        $user = User::create([
            'name' => $pendingUser->name,
            'lastname' => $pendingUser->lastname,
            'username' => $pendingUser->username,
            'email' => $pendingUser->email,
            'password' => $pendingUser->password, // Keep the hashed password
            'phone_number' => $pendingUser->phone_number,
            'date_of_birth' => $pendingUser->date_of_birth,
            'gender' => $pendingUser->gender,
            'role' => $pendingUser->role,
        ]);
        
        

        $pendingUser->delete();

        return response()->json(['message' => 'User accepted', 'user' => $user])
            ->header('Access-Control-Allow-Origin', 'http://localhost:3000')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
    } catch (\Exception $e) {
        \Log::error('Error accepting pending user: ' . $e->getMessage());
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}



    // Decline a pending user and delete from pending_users table
    public function declinePendingUser($id)
    {
        // Find the pending user by ID
        $pendingUser = PendingUser::find($id);

        if (!$pendingUser) {
            return response()->json(['error' => 'Pending user not found'], 404);
        }

        // Delete the pending user
        $pendingUser->delete();

        return response()->json(['message' => 'User declined and removed from pending users table']);
    }
}