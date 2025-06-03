<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'comment'    => 'required|string|max:1000',
        ];
    }

    /**
     * Thông báo lỗi validation bằng tiếng Việt.
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Vui lòng chọn sản phẩm để bình luận.',
            'product_id.exists'   => 'Sản phẩm không tồn tại.',
            'comment.required'    => 'Nội dung bình luận không được để trống.',
            'comment.string'      => 'Bình luận phải là một chuỗi ký tự hợp lệ.',
            'comment.max'         => 'Bình luận không được vượt quá 1000 ký tự.',
        ];
    }
}
