<?php

namespace App\Http\Controllers;

use App\Models\Banners;
use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banners::orderBy('position','asc')->paginate(10);;
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BannerRequest $request)
    {

        $banner = new Banners();
        $banner->fill($request->all());
        if ($request->hasFile('image')) {
            // Lưu ảnh vào thư mục storage/app/public/uploads/banners
            $path = $request->file('image')->store('banners', 'public');
            $banner->image = $path;
        }

        $banner->save();

        return redirect()->route('banners.index')->with('success', 'Banner created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $banner = Banners::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BannerRequest $request, Banners $banner)
    {

        $banner->fill($request->validated());

    // Nếu có file ảnh mới, lưu ảnh và cập nhật đường dẫn
    if ($request->hasFile('image')) {
        // Xóa ảnh cũ nếu có
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        // Lưu ảnh vào thư mục storage/app/public/uploads/banners
            $path = $request->file('image')->store('banners', 'public');
            $banner->image = $path;
    }


        $banner->save();

        return redirect()->route('banners.index')->with('success', 'Sửa Banner thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banners $banner)
    {

        // Xóa file ảnh nếu có
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();
        return redirect()->route('banners.index')->with('success', 'Xóa Banner thành công.');
    }
}
