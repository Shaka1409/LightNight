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
        $rules = [
            'name'           => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'phonenumber'    => 'required|string|max:11',
            'payment_method' => 'required|in:cod,bank',
            'shipping_area'  => 'required|in:hanoi,mienbac,toanquoc',
            'product_id'     => 'nullable|exists:products,id',
            'quantity'       => 'nullable|integer|min:1',
        ];

        if ($this->input('payment_method') === 'bank') {
            $rules['payment_proof'] = 'required|array|min:1|max:2';
            $rules['payment_proof.*'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
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
            'payment_proof.required' => 'Vui lòng tải lên ít nhất 1 ảnh xác nhận chuyển khoản.',
            'payment_proof.array'    => 'Ảnh chuyển khoản không hợp lệ.',
            'payment_proof.min'      => 'Phải có ít nhất 1 ảnh chuyển khoản.',
            'payment_proof.max'      => 'Chỉ được tải lên tối đa 2 ảnh.',
            'payment_proof.*.image'  => 'Tệp phải là ảnh.',
            'payment_proof.*.mimes'  => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
            'payment_proof.*.max'    => 'Mỗi ảnh không được vượt quá 2MB.',
        ];
    }
}
