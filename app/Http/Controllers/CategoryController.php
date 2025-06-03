<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::query();

        // Nếu có từ khóa tìm kiếm, áp dụng điều kiện
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->Where('name', 'like', "%$keyword%")
            ->orWhere('description', 'like', "%$keyword%");
        }
        $categories = $query->orderBy('id', 'ASC')->paginate(10);
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = new Category;
        $category->fill($request->all());
        $category->save();
        return redirect('admin/category')->with('success', 'Danh mục đã được tạo thành công.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        // Nếu cần hiển thị chi tiết category
        return view('admin.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->name = $request->input('name');
        $category->status = $request->input('status');
        $category->description = $request->input('description');
        $category->save();  // Lưu vào databas
        return redirect('admin/category')->with('success', 'Danh mục đã được cập nhật thành công.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect('admin/category')->with('success', 'Danh mục đã được xoá');
    }
}
