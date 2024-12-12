<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordController extends Controller
{
    //
    public function update(Request $request)
    {
        // Validate the form data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);
    
        // Check if the token is valid
        $passwordReset = DB::table('password_resets')->where('email', $request->email)->first();
    
        if (!$passwordReset || $passwordReset->token !== $request->token) {
            return back()->withErrors(['email' => 'Invalid or expired token.']);
        }
    
        // Update the password
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }
    
        // Update the user's password (let the model handle hashing via the setPasswordAttribute method)
        $user->password = $request->password; // This triggers the setPasswordAttribute method
        $user->save();
    
        // Delete the token from the password_resets table after use
        DB::table('password_resets')->where('email', $request->email)->delete();
        
        \Log::info('New password hash', ['password' => $user->password]);
    
        // Redirect to success page
        return redirect()->route('password.success');
    }
    
    
}