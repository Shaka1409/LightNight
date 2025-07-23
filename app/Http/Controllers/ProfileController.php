<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ProfileController extends Controller
{

    public function profile()
    {
        return view('profile');
    }

    public function update(UpdateProfileRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update($request->validated());

        return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng!']);
        }
        /** @var \App\Models\User $user */
        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }


    public function checkEmailExists(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $emailDomain = substr(strrchr($request->email, "@"), 1);
        if (!checkdnsrr($emailDomain, 'MX')) {
            return response()->json(['exists' => false, 'message' => 'Email không hợp lệ hoặc không tồn tại!'], 400);
        }

        return response()->json(['exists' => true, 'message' => 'Email hợp lệ!']);
    }
}
