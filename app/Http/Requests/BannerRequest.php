<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    /**
     * Xác thực người dùng có quyền gửi request này không.
     */
    public function authorize()
    {
        return true; // Cho phép tất cả người dùng
    }

    /**
     * Quy tắc validation.
     */
    public function rules()
    {
        return [
            'name'        => 'required|string|max:255',
            'image'       => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'position'    => 'nullable|string|max:255',
            'description' => 'required|string',
        ];
    }

    /**
     * Thông báo lỗi tiếng Việt.
     */
    public function messages()
    {
        return [
            'name.required'        => 'Tên không được để trống.',
            'name.string'          => 'Tên phải là một chuỗi.',
            'name.max'             => 'Tên không được vượt quá 255 ký tự.',
            'image.image'          => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes'          => 'Hình ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
            'image.max'            => 'Kích thước hình ảnh không được vượt quá 2MB.',
            'position.string'      => 'Vị trí phải là một chuỗi.',
            'position.max'         => 'Vị trí không được vượt quá 255 ký tự.',
            'description.required' => 'Mô tả không được để trống.',
            'description.string'   => 'Mô tả phải là một chuỗi.',
        ];
    }
}
