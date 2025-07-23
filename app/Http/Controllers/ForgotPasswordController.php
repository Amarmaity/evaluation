<?php

namespace App\Http\Controllers;

use App\Models\AllClient;
use Illuminate\Http\Request;
use App\Models\SuperAddUser;
use App\Models\SuperUserTable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;




class ForgotPasswordController extends Controller
{
    //

    public function showForgotPasswordForm()
    {
        return view('forgotpassword.forgotPassword');
    }


    public function showResetPasswordForm()
    {
        return view('forgotpassword.resetPassword');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        // Search across all 3 tables
        $user = SuperAddUser::where('email', $email)->first();
        $userType = null;
        $userTable = null;

        if ($user) {
            $userType = $user->user_type ?? 'super_add_users';
            $userTable = 'SuperAddUser';
        } else {
            $user = SuperUserTable::where('email', $email)->first();
            if ($user) {
                $userType = $user->user_type ?? 'super_user_tables';
                $userTable = 'SuperUserTable';
            } else {
                $user = AllClient::where('client_email', $email)->first();
                if ($user) {
                    $userType = $user->user_type ?? 'all_clients';
                    $userTable = 'AllClient';
                }
            }
        }

        // No user found in any table
        if (!$user) {
            return back()->withErrors(['email' => 'This email does not exist in our records.'])->withInput();
        }

        $otp = random_int(100000, 999999);
        Session::put('forgot_password_otp', $otp);
        Session::put('forgot_password_email', $email);
        Session::put('forgot_password_user_type', $userType);
        Session::put('forgot_password_user_table', $userTable);
        Session::put('otp_sent_time', now());

        try {
            Mail::to($email)->send(new \App\Mail\OtpMail($otp));
            return redirect()->route('forgot-password.verify')->with('success', 'OTP sent to your email.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send OTP. Please try again later.');
        }
    }

    public function showVerifyOtpForm()
    {
        return view('forgotpassword.verify');
    }

    public function verifyOther(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        if (Session::get('forgot_password_otp') == $request->otp) {
            $email = Session::get('forgot_password_email');

            if ($user = SuperAddUser::where('email', $email)->first()) {
                Session::put('forgot_password_user_type', $user->user_type);
                Session::put('forgot_password_user_table', 'SuperAddUser');
                Session::put('forgot_password_login_url', route('user-login'));
            } elseif ($user = SuperUserTable::where('email', $email)->first()) {
                Session::put('forgot_password_user_type', $user->user_type);
                Session::put('forgot_password_user_table', 'SuperUserTable');
                Session::put('forgot_password_login_url', route('user-login'));
            } elseif ($user = AllClient::where('client_email', $email)->first()) {
                Session::put('forgot_password_user_type', $user->user_type);
                Session::put('forgot_password_user_table', 'AllClient');
                Session::put('forgot_password_login_url', route('all-user-login'));
                return back()->with('error', 'User not found.');
            }

            Session::put('forgot_password_redirect_url', url()->previous());

            return redirect()->route('forgot-password.reset');
        } else {
            return back()->with('error', 'Invalid OTP.');
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:4|confirmed'
        ],['password.confirmed' => 'Passwords do not match.',]);

        $email = Session::get('forgot_password_email');
        $userTable = Session::get('forgot_password_user_table');

        if (!$email || !$userTable) {
            return back()->with('error', 'Session expired. Please try again.');
        }

        // Identify which table
        if ($userTable === 'SuperAddUser') {
            $user = SuperAddUser::where('email', $email)->first();
        } elseif ($userTable === 'SuperUserTable') {
            $user = SuperUserTable::where('email', $email)->first();
        } elseif ($userTable === 'AllClient') {
            $user = AllClient::where('client_email', $email)->first();
        } else {
            return back()->with('error', 'Unexpected error. Please try again.');
        }

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            Session::forget([
                'forgot_password_otp',
                'forgot_password_email',
                'forgot_password_user_type',
                'forgot_password_user_table',
                'forgot_password_redirect_url'
            ]);

            return redirect()->route('all-user-login')->with('success', 'Password reset successfully.');
        }

        return back()->with('error', 'Something went wrong. Try again.');
    }
}
