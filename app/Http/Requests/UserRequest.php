<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cho phép tất cả request, nếu cần có thể kiểm tra quyền ở đây
    }

    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:users,email,' . $userId,
            'phonenumber' => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:255',
            'role'        => 'required|string|in:user,admin',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Vui lòng nhập tên.',
            'name.max'             => 'Tên không được vượt quá 255 ký tự.',

            'email.required'       => 'Vui lòng nhập email.',
            'email.email'          => 'Email không hợp lệ.',
            'email.max'            => 'Email không được vượt quá 255 ký tự.',
            'email.unique'         => 'Email này đã được sử dụng.',

            'phonenumber.string'   => 'Số điện thoại phải là chuỗi.',
            'phonenumber.max'      => 'Số điện thoại không được dài quá 20 ký tự.',

            'address.string'       => 'Địa chỉ phải là chuỗi.',
            'address.max'          => 'Địa chỉ không được vượt quá 255 ký tự.',

            'role.required'        => 'Vui lòng chọn vai trò.',
            'role.in'              => 'Vai trò không hợp lệ. Chỉ được chọn "user" hoặc "admin".',
        ];
    }
}
