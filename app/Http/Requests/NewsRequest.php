<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
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
            'link'        => 'nullable|url',
            'image'       => 'sometimes|image',
            'content'     => 'required',
            'description' => 'required|string',
            'status'      => 'required|boolean',
        ];
    }

    /**
     * Thông báo lỗi tiếng Việt.
     */
    public function messages()
    {
        return [
            'name.required'        => 'Tên tin tức không được để trống.',
            'name.string'          => 'Tên tin tức phải là chuỗi ký tự.',
            'name.max'             => 'Tên tin tức không được vượt quá 255 ký tự.',

            'link.url'             => 'Đường dẫn không hợp lệ.',

            'image.sometimes'      => 'Bạn có thể chọn hình ảnh.',
            'image.image'          => 'Tệp tải lên phải là hình ảnh hợp lệ.',

            'content.required'     => 'Nội dung tin tức không được để trống.',

            'description.required' => 'Mô tả không được để trống.',
            'description.string'   => 'Mô tả phải là một chuỗi ký tự.',

            'status.required'      => 'Trạng thái là bắt buộc.',
            'status.boolean'       => 'Trạng thái phải là đúng hoặc sai.',
        ];
    }
}
