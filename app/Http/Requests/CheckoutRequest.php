<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'name'           => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'phonenumber'    => 'required|string|max:11',
            'payment_method' => 'required|in:cod',
            'product_id'     => 'nullable|exists:products,id',
            'quantity'       => 'nullable|integer|min:1',
        ];
    }

    /**
     * Thông báo lỗi validation bằng tiếng Việt.
     */
    public function messages(): array
    {
        return [
            'name.required'           => 'Vui lòng nhập họ và tên.',
            'name.string'             => 'Họ và tên phải là một chuỗi ký tự.',
            'name.max'                => 'Họ và tên không được vượt quá 255 ký tự.',
            'address.required'        => 'Vui lòng nhập địa chỉ giao hàng.',
            'address.string'          => 'Địa chỉ phải là một chuỗi ký tự.',
            'address.max'             => 'Địa chỉ không được vượt quá 255 ký tự.',
            'phonenumber.required'    => 'Vui lòng nhập số điện thoại.',
            'phonenumber.string'      => 'Số điện thoại phải là một chuỗi ký tự.',
            'phonenumber.max'         => 'Số điện thoại không được vượt quá 11 ký tự.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.in'       => 'Phương thức thanh toán không hợp lệ.',
            'product_id.exists'       => 'Sản phẩm không tồn tại.',
            'quantity.integer'        => 'Số lượng sản phẩm phải là một số nguyên.',
            'quantity.min'            => 'Số lượng sản phẩm tối thiểu là 1.',
        ];
    }
}
