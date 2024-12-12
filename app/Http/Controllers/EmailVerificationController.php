<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use  Illuminate\Http\Request;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Log;
class EmailVerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|unique:users,email', // Add unique:users,email
            ]);
    
            // Check if the email is already in use
            if (DB::table('email_verifications')->where('email', $request->email)->exists()) {
                throw new \Exception('Email is already in use');
            }
    
            $verificationCode = mt_rand(100000, 999999);
                
            DB::table('email_verifications')->updateOrInsert(
                ['email' => $request->email],
                [
                    'verification_code' => $verificationCode,
                    'expires_at' => Carbon::now()->addMinutes(15),
                    'is_verified' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
    
            Mail::to($request->email)->send(new EmailVerification($verificationCode));
    
            return response()->json(['message' => 'Verification code sent to email.']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function verifyCode(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:email_verifications,email',
                'code' => 'required|size:6',
            ]);

            $verification_code = DB::table('email_verifications')
            ->where('email', $request->email)
            ->where('verification_code', $request->code)
            ->where('expires_at', '>', now())
            ->first();
            if (!$verification_code) {
                throw new \Exception('Invalid or expired verification code.');
            }
            return response(["this_is_valid" => $verification_code, "success" => "true"]  , 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response(["message" => $th->getMessage()], 400);
        }
        // Log::info('verifyCode route accessed', ['email' => $request->email, 'code' => $request->code]);

        // return response()->json(['message' => 'Route accessed successfully']);    
    }


    public function verifyCode1(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|size:6|numeric',
        ]);

        $verification = DB::table('email_verifications')
            ->where('email', $request->email)
            ->where('verification_code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if ($verification) {
            DB::table('email_verifications')
                ->where('email', $request->email)
                ->update(['is_verified' => true]);

            return response()->json(['success' => true, 'message' => 'Email verified successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid or expired verification code.'], 400);
    }
}
