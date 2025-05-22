<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\DelostyleSuperUserTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function index()
    {
        return view("admin/loginForm");
    }

    /**
     * Load login form with active user types
     */
    public function login()
    {
        $delostyleUserTable = new DelostyleSuperUserTable();
        $user['User_id'] = $delostyleUserTable->getActiveUserTypes();
        return view('/admin/loginForm', $user);
    }

    /**
     * Handle login authentication and OTP generation
     */
    public function loginAutenticacao(Request $request)
    {
        dd($request->all());

        // Validate the request
        $validated = $request->validate([
            'User_id' => 'required|integer',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Find user by email
        $user = User::where('email', $validated['email'])->first();

        if ($user && Hash::check($validated['password'], $user->password)) {
            if ($user->flag == 1 && $user->user_type_id == $validated['User_id']) {
                $otp = random_int(100000, 999999);

                Session::put('otp', $otp);
                Session::put('otp_sent', true);
                Session::put('otp_email', $validated['email']); 

                // Send OTP via email
                try {
                    Mail::to($validated['email'])->send(new OtpMail($otp));

                    return response()->json([
                        'status' => 'success',
                        'message' => 'OTP has been sent to your email!',
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to send OTP email. Please try again later.',
                        'debug' => env('APP_DEBUG') ? $e->getMessage() : null, 
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Your account is inactive or user type is incorrect.',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid login credentials.',
            ]);
        }
    }

    /**
     * Verify OTP and log in the user
     */
    public function verifyOtp(Request $request)
    {
        
        $validated = $request->validate([
            'User_id' => 'required|integer',
            'email' => 'required|email',
            'password' => 'nullable|string'
        ]);

        
        $otpSession = Session::get('otp');
        $otpEmail = Session::get('otp_email');

        
        if ($validated['otp'] == $otpSession && $validated['email'] == $otpEmail) {
            Session::forget('otp');
            Session::forget('otp_email');

            
            $user = User::where('email', $validated['email'])->first();

            if ($user) {
                
                Session::put('user_id', $user->id);

                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP verified successfully. You are now logged in!',
                    'user' => $user,
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.',
                ]);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid OTP. Please try again.',
            ]);
        }
    }
}
