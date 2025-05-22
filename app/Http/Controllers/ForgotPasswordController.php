<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuperAddUser;
use App\Models\SuperUserTable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;



class ForgotPasswordController extends Controller
{
    //

    public function showForgotPasswordForm()
    {
        return view('forgotpassword.forgotPassword');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:super_add_users,email'
        ]);

        $otp = random_int(100000, 999999);
        Session::put('forgot_password_otp', $otp);
        Session::put('forgot_password_email', $request->email);
        Session::put('otp_sent_time', now());

        try {
            Mail::to($request->email)->send(new \App\Mail\OtpMail($otp));
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
            Session::put('forgot_password_login_url', route('super-user-login')); 
        } else {
            return back()->with('error', 'User not found.');
        }

        
        Session::put('forgot_password_redirect_url', url()->previous());

        
        return redirect()->route('forgot-password.reset');
    } else {
        return back()->with('error', 'Invalid OTP.');
    }
}



    public function showResetPasswordForm()
    {
        return view('forgotpassword.resetPassword');
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:4|confirmed'
        ]);

        $email = Session::get('forgot_password_email');
    
        if (!$email) {
            return back()->with('error', 'Session expired. Please try again.');
        }

        $userType = Session::get('forgot_password_user_type');
        
        if (in_array($userType, ['Super User', 'admin', 'users', 'manager', 'client', 'hr'])) {
            $user = SuperAddUser::where('email', $email)->first();
        } else {
            $user = SuperUserTable::where('email', $email)->first();
        }
    
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            Session::forget([
                'forgot_password_otp',
                'forgot_password_email',
                'forgot_password_user_type',
                'forgot_password_redirect_url'
            ]);
    
            return redirect()->route('all-user-login')->with('success', 'Password reset successfully.');
        }
    
        return back()->with('error', 'Something went wrong. Try again.');
    }
    
}
