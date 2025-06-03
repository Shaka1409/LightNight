<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;


class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng, có hỗ trợ lọc theo vai trò.
     */
    public function index(Request $request)
{
    $query = User::query();

    // Nếu có role, lọc theo vai trò
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    // Thêm tìm kiếm theo tên hoặc email nếu muốn:
    if ($request->filled('q')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->q . '%')
              ->orWhere('email', 'like', '%' . $request->q . '%');
        });
    }

    $users = $query->orderBy('id', 'asc')->paginate(10);

    return view('admin.users.index', compact('users'));
}


    /**
     * Hiển thị form tạo người dùng.
     */
    public function create()
    {
        // Trả về view admin/user/create.blade.php
        return view('admin.users.create');
    }

    /**
     * Lưu thông tin người dùng mới vào cơ sở dữ liệu.
     */
    public function store(UserRequest $request, User $user)
    {
        $user->update($request->all());

    
        // Băm mật khẩu trước khi lưu
        $validatedData['password'] = Hash::make($request->password);
    
        // Tạo người dùng mới
        User::create($validatedData);
    
        // Chuyển hướng về trang danh sách user kèm thông báo
        return redirect()->route('user.index')->with('success', 'Tạo người dùng thành công!');
    }
    

    /**
     * Hiển thị form chỉnh sửa thông tin người dùng.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng trong cơ sở dữ liệu.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->all());



        return redirect()->route('user.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    /**
     * Xóa người dùng khỏi cơ sở dữ liệu.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Nếu người dùng có avatar, tiến hành xóa avatar trong thư mục public (nếu tồn tại)
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('user.index')->with('success', 'Xóa người dùng thành công!');
    }
}
