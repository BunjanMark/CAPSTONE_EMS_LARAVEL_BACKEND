<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function sendResetCode(Request $request)
    {
        $validated = $request->validate(['email' => 'required|email']);
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            Log::warning('Password reset request for non-existent email', ['email' => $validated['email']]);
            return response()->json(['message' => 'Email not found'], 404);
        }

        $resetCode = random_int(100000, 999999);

        // Store the reset code in the database
        DB::table('password_resets')->updateOrInsert(
            ['email' => $validated['email']],
            [
                'reset_code' => $resetCode,
                'expires_at' => Carbon::now()->addMinutes(15),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Send the reset code via email
        Mail::to($validated['email'])->send(new \App\Mail\ResetCodeMail($resetCode));

        Log::info('Password reset code sent', ['email' => $validated['email']]);
        return response()->json(['message' => 'Reset code sent successfully'], 200);
    }

    public function verifyResetCode(Request $request)
    {
        Log::info('Reset code verification request', ['email' => $request->email, 'code' => $request->code]);

        // Validate incoming data
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string',
        ]);

        $email = trim($request->email); // Trim spaces
        $code = $request->code;

        // Find the reset code record from the password_resets table
        $resetRecord = DB::table('password_resets')->where('email', $email)->first();

        if (!$resetRecord) {
            Log::warning('Password reset code verification failed: No reset request found', ['email' => $email]);
            return response()->json(['message' => 'No reset request found for this email'], 404);
        }

        // Check if the reset code matches
        if ($resetRecord->reset_code !== $code) {
            Log::warning('Password reset code verification failed: Invalid reset code', ['email' => $email, 'code' => $code]);
            return response()->json(['message' => 'Invalid reset code'], 400);
        }

        // Check if the reset code has expired
        if (Carbon::now()->greaterThan($resetRecord->expires_at)) {
            Log::warning('Password reset code verification failed: Reset code expired', ['email' => $email, 'code' => $code]);
            return response()->json(['message' => 'Reset code expired'], 400);
        }

        Log::info('Password reset code verified successfully', ['email' => $email]);
        return response()->json(['message' => 'Code verified successfully']);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'code' => 'required|string',
            'password' => 'required|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
        ]);
    
        $email = trim($validated['email']);
        $code = $validated['code'];
        $newPassword = $validated['password'];
    
        $resetRecord = DB::table('password_resets')->where('email', $email)->first();
    
        if (!$resetRecord) {
            return response()->json(['message' => 'No reset request found for this email'], 404);
        }
    
        if ($resetRecord->reset_code !== $code) {
            return response()->json(['message' => 'Invalid reset code'], 400);
        }
    
        if (Carbon::now()->greaterThan($resetRecord->expires_at)) {
            return response()->json(['message' => 'Reset code expired'], 400);
        }
    
        $user = User::where('email', $email)->first();
    
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        $user->password = Hash::make($newPassword);
        $user->save();
    
        DB::table('password_resets')->where('email', $email)->delete();
    
        return response()->json(['message' => 'Password reset successfully']);
    }
     
}
