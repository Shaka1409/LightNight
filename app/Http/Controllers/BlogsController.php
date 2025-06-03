<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\BlogRequest;

class BlogsController extends Controller
{
    /**
     * Hiển thị danh sách Blog.
     */
    public function index(Request $request)
    {
        $query = Blogs::query();

        // Nếu có từ khóa tìm kiếm, áp dụng điều kiện
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->Where('name', 'like', "%$keyword%");
        }

        // Sắp xếp theo id và phân trang
        $blogs = $query->orderBy('id', 'asc')->paginate(10);

        return view('admin.blogs.index', compact('blogs'));
    }


    /**
     * Hiển thị form tạo mới Blog.
     */
    public function create()
    {
        // Trả về view: resources/views/admin/blogs/create.blade.php
        return view('admin.blogs.create');
    }

    /**
     * Lưu một Blog mới vào cơ sở dữ liệu.
     */
    public function store(BlogRequest $request)
    {
        $validated = $request->validated(); // Lấy dữ liệu đã xác thực

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        Blogs::create($validated);

        return redirect()->route('blogs.index')->with('success', 'Bài viết đã được tạo');
    }

    /**
     * Hiển thị chi tiết một Blog (nếu cần).
     */
    public function show($id)
    {
        $blog = Blogs::findOrFail($id);
        return view('admin.blogs.show', compact('blog'));
    }

    /**
     * Hiển thị form sửa Blog.
     */
    public function edit($id)
    {
        $blog = Blogs::findOrFail($id);
        return view('admin.blogs.edit', compact('blog'));
    }

    /**
     * Cập nhật Blog đã tồn tại.
     */
    public function update(BlogRequest $request, $id)
    {
        $blog = Blogs::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $validated['image'] = $request->file('image')->store('blogs', 'public');
        }

        $blog->update($validated);

        return redirect()->route('blogs.index')->with('success', 'Bài viết đã được cập nhật');
    }

    /**
     * Xóa Blog khỏi cơ sở dữ liệu.
     */
    public function destroy(Blogs $blog)
    {
       // Xóa file ảnh nếu có
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        // Trả về view index, kèm thông báo
        return view('admin.blogs.index')
            ->with('success', 'Xoá bài viết thành công');
    }
}
