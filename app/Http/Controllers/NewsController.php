<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\NewsRequest;

class NewsController extends Controller
{
    /**
     * Hiển thị danh sách New.
     */
    public function index(Request $request)
    {
        $query = News::query();

        // Nếu có từ khóa tìm kiếm, áp dụng điều kiện
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->Where('name', 'like', "%$keyword%");
        }

        // Sắp xếp theo id và phân trang
        $news = $query->orderBy('id', 'asc')->paginate(10);

        return view('admin.news.index', compact('news'));
    }


    /**
     * Hiển thị form tạo mới new.
     */
    public function create()
    {
        // Trả về view: resources/views/admin/news/create.blade.php
        return view('admin.news.create');
    }

    /**
     * Lưu một new mới vào cơ sở dữ liệu.
     */
    public function store(NewsRequest $request)
    {
        $validated = $request->validated(); // Lấy dữ liệu đã xác thực

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        News::create($validated);

        return redirect()->route('news.index')->with('success', 'Tin tức đã được tạo');
    }

    /**
     * Hiển thị chi tiết một new (nếu cần).
     */
    public function show($id)
    {
        $new = News::findOrFail($id);
        return view('admin.news.show', compact('new'));
    }

    /**
     * Hiển thị form sửa new.
     */
    public function edit($id)
    {
        $new = News::findOrFail($id);
        return view('admin.news.edit', compact('new'));
    }

    /**
     * Cập nhật new đã tồn tại.
     */
    public function update(NewsRequest $request, $id)
    {
        $new = News::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($new->image && Storage::disk('public')->exists($new->image)) {
                Storage::disk('public')->delete($new->image);
            }
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        $new->update($validated);

        return redirect()->route('news.index')->with('success', 'Tin tức đã được cập nhật');
    }

    /**
     * Xóa new khỏi cơ sở dữ liệu.
     */
    public function destroy(News $new)
    {
       // Xóa file ảnh nếu có
        if ($new->image && Storage::disk('public')->exists($new->image)) {
            Storage::disk('public')->delete($new->image);
        }

        $new->delete();

        // Trả về view index, kèm thông báo
        return view('admin.news.index')
            ->with('success', 'Xoá tin tức thành công');
    }
}
