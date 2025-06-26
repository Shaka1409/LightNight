<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductImage;



class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Lọc theo danh mục nếu có
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Tìm kiếm theo tên sản phẩm nếu có từ khóa
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('status') || $request->status === '0') {
            $query->where('status', $request->status);
        }


        $products = $query->orderBy('id', 'desc')->paginate(10);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Lấy danh sách danh mục để chọn cho sản phẩm 
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $product = new Product;
        $product->fill($request->all());
        // Nếu có file ảnh, lưu file và gán đường dẫn vào field image
        if ($request->hasFile('image')) {
            // Lưu ảnh vào thư mục storage/app/public/uploads/products
            $path = $request->file('image')->store('uploads/products', 'public');
            $product->image = $path;
        }
        $product->save();
        // Lưu ảnh phụ nếu có
        if ($request->hasFile('sub_images')) {
            $subImages = $request->file('sub_images');

            if (count($subImages) > 4) {
                return back()->withErrors(['sub_images' => 'Chỉ được chọn tối đa 4 ảnh phụ.'])->withInput();
            }

            foreach ($subImages as $subImage) {
                $subPath = $subImage->store('uploads/products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $subPath
                ]);
            }
        }

        return redirect()->route('product.index')->with('success', 'Thêm sản phẩm thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Trả về view show với dữ liệu sản phẩm
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Lấy danh sách danh mục để chỉnh sửa
        $categories = Category::all();
        $product->load('images'); // load mối quan hệ ảnh phụ
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        $product->fill($request->validated());

        // Nếu có file ảnh mới, lưu ảnh và cập nhật đường dẫn
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $path = $request->file('image')->store('uploads/products', 'public');
            $product->image = $path;
        }

        $product->save();

        if ($request->hasFile('sub_images')) {
            $existingCount = $product->images()->count();
            $newCount = count($request->file('sub_images'));

            if (($existingCount + $newCount) > 4) {
                return back()->withErrors(['sub_images' => 'Tổng ảnh phụ không được vượt quá 4 ảnh.'])->withInput();
            }

            foreach ($request->file('sub_images') as $subImage) {
                $subPath = $subImage->store('uploads/products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $subPath
                ]);
            }
        }


        return redirect()->route('product.index')->with('success', 'Cập nhật sản phẩm thành công.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Xóa file ảnh nếu có
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Xóa ảnh phụ
        foreach ($product->images as $subImage) {
            if (Storage::disk('public')->exists($subImage->image_path)) {
                Storage::disk('public')->delete($subImage->image_path);
            }
            $subImage->delete(); // Xóa record trong DB
        }

        $product->delete();
        return redirect()->route('product.index')->with('success', 'Sản phẩm đã được xoá.');
    }

    public function deleteSubImage($id)
    {
        $image = ProductImage::where('id', $id)->firstOrFail();

        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return back()->with('success', 'Ảnh phụ đã được xoá.');
    }
}
