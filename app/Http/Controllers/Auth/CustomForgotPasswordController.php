<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendOtpMail;

class CustomForgotPasswordController extends Controller
{
    public function showOtpRequestForm() {
        return view('auth.otp-request');
    }

    public function sendOtp(Request $request) {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) return back()->with('error', 'Email không tồn tại.');

        $otp = rand(100000, 999999);

        session([
            'otp' => $otp,
            'otp_email' => $user->email,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        Mail::to($user->email)->queue(new SendOtpMail($otp, $user));
        return redirect()->route('otp.verify.form')->with('success', 'OTP đã gửi đến email.');
    }

    public function showOtpVerifyForm() {
        return view('auth.otp-verify');
    }

    public function verifyOtp(Request $request) {
        $request->validate(['otp' => 'required']);

        if (!session('otp') || !session('otp_email') || !session('otp_expires_at')) {
            return back()->with('error', 'Không tìm thấy OTP.');
        }

        if (now()->greaterThan(session('otp_expires_at'))) {
            return back()->with('error', 'OTP đã hết hạn.');
        }

        if ($request->otp != session('otp')) {
            return back()->with('error', 'OTP không chính xác.');
        }

        return redirect()->route('otp.new.form');
    }

    public function showResetForm() {
        return view('auth.otp-reset', ['email' => session('otp_email')]);
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) return back()->with('error', 'Không tìm thấy người dùng.');

        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget(['otp', 'otp_email', 'otp_expires_at']);
        return redirect('/login')->with('success', 'Đặt lại mật khẩu thành công!');
    }
}
