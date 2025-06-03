<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'phonenumber' => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'        => 'Vui lòng nhập họ và tên.',
            'name.max'             => 'Tên không được vượt quá 255 ký tự.',
            'email.required'       => 'Vui lòng nhập email.',
            'email.email'          => 'Email không hợp lệ.',
            'email.unique'         => 'Email này đã được sử dụng.',
            'phonenumber.max'      => 'Số điện thoại không được quá 20 ký tự.',
            'address.max'          => 'Địa chỉ không được quá 255 ký tự.',
        ];
    }
}
