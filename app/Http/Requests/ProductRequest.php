<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|unique:products,name,' . ($this->route('product') ? $this->route('product')->id : ''),
            'price'         => 'required|numeric',
            'sale_price'    => 'nullable|numeric|lt:price',
            'color'         => 'required',
            'size'          => 'required',
            'material'      => 'required',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'stock_quantity'=> 'required|integer|min:0',
            'sold_count'  => 'nullable|integer|min:0',
            'status'        => 'required',
            'description'   => 'nullable|string'
        ];
    }
    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists'   => 'Danh mục không hợp lệ.',

            'name.required'  => 'Vui lòng nhập tên sản phẩm.',
            'name.unique'    => 'Tên sản phẩm đã tồn tại.',

            'price.required' => 'Vui lòng nhập giá sản phẩm.',
            'price.numeric'  => 'Giá sản phẩm phải là một số.',

            'sale_price.numeric' => 'Giá khuyến mãi phải là một số.',
            'sale_price.lt'      => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',

            'color.required'    => 'Vui lòng chọn màu sắc.',
            'size.required'     => 'Vui lòng nhập kích thước.',
            'material.required' => 'Vui lòng nhập chất liệu.',

            'image.required' => 'Vui lòng chọn ảnh sản phẩm.',
            'image.image'    => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes'    => 'Ảnh phải có định dạng: jpeg, png, jpg, gif, svg.',
            
            'stock_quantity.min'     => 'Số lượng tồn kho không được nhỏ hơn 0.',
            'stock_quantity.integer' => 'Số lượng tồn kho phải là một số nguyên.',
            'stock_quantity.required' => 'Vui lòng nhập số lượng tồn kho.',

            'sold_count.min'     => 'Số lượng đã bán không được nhỏ hơn 0.',
            'sold_count.integer' => 'Số lượng đã bán phải là một số nguyên.',

            'status.required' => 'Vui lòng chọn trạng thái.',

            'description.string' => 'Mô tả phải là một chuỗi văn bản.',
        ];
    }
    public function getSlugAttribute()
{
    return Str::slug($this->name);
}
}
