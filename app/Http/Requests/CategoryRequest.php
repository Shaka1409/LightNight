<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Xác thực quyền gửi request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Quy tắc validation.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:categories,name,' . optional($this->route('category'))->id,
            'status'      => 'required|boolean',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Thông báo lỗi validation bằng tiếng Việt.
     */
    public function messages(): array
    {
        return [
            'name.required'   => 'Tên danh mục không được để trống.',
            'name.unique'     => 'Tên danh mục đã tồn tại, vui lòng chọn tên khác.',
            'status.required' => 'Trạng thái danh mục là bắt buộc.',
            'status.boolean'  => 'Trạng thái không hợp lệ.',
            'description.string' => 'Mô tả phải là chuỗi văn bản.',
        ];
    }
}
