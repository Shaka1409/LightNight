<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function formLogin()
    {
        return view('auth.login');
    }
    public function FormLoginAdmin()
    {
        return view('admin.auth.login');
    }


    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // nếu lf admin chuyển hướng vào admin dashboard
            // $user = Auth::user();
            // if ($user->role === 'admin') {
            //     return redirect()->route('admin.dashboard')->with('success', 'Bạn đã đăng đăng nhập thành công.');
            // }
            return redirect()->intended('/')->with('success', 'Bạn đã đăng đăng nhập thành công.');
            
        }

        return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác.'])->withInput();
    }
    
    public function loginAdmin(LoginRequest $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $user = Auth::user();

        // Kiểm tra quyền admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Bạn đã đăng nhập thành công.');
        }

        // Nếu không phải admin -> đăng xuất ngay + báo lỗi
        Auth::logout();
        return back()->with('error', 'Bạn không có quyền truy cập.');
    }

    // Đăng nhập thất bại
    return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác.'])->withInput();
}


    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/')->with('success', 'Bạn đã đăng xuất thành công.');
    }
}
