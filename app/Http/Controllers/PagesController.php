<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Comment;
use App\Models\News;
use App\Models\Banners;
use App\Models\ProductImage;


class PagesController extends Controller
{
    public function index()
    {
        $products = Product::whereIn('status', [0, 1])
            ->whereHas('category', function ($query) {
                $query->where('status', 1);
            })
            ->get();
        // Lấy tất cả danh mục kèm theo sản phẩm
        $categories = Category::with(['products' => function ($query) {
            $query->whereIn('status', [0, 1]);
        }])
            ->where('status', [1])
            ->get();
        // Lấy tất cả bài viết New, sắp xếp theo thời gian tạo giảm dần
        $news = News::where('status', '=', 1)->get();
        $outstandingComments = Comment::where('status', '=', 3)->get();
        $banners = Banners::whereIn('position', [1, 2, 3, 4, 5, 6, 7])->get()->keyBy('position');
        return view('home', compact('categories', 'products', 'news', 'outstandingComments', 'banners'));
    }

    public function show()
    {
        $categories = Category::where('status', 1)
            ->whereHas('products', function ($query) {
                $query->whereIn('status', [0, 1]);
            })
            ->with(['products' => function ($query) {
                $query->whereIn('status', [0, 1]);
            }])
            ->get();
        $allProducts = Product::with('category')
            ->whereIn('status', [0, 1])
            ->whereHas('category', function ($query) {
                $query->where('status', 1);
            })
            ->get();
        $bestSellingProducts = Product::with('category')
            ->whereIn('status', [0, 1])
            ->whereHas('category', function ($query) {
                $query->where('status', 1);
            })
            ->orderByDesc('sold_count')
            ->take(3)
            ->get();

        // Trả về view product.blade.php và truyền biến $categories, $allProducts sang
        return view('product', compact('categories', 'allProducts', 'bestSellingProducts'));
    }


    public function productsByCategory($id)
    {
        // Lấy danh mục đã chọn (kèm sản phẩm)
        $selectedCategory = Category::with('products')->findOrFail($id);
        // Lọc sản phẩm thuộc danh mục này
        $products = $selectedCategory->products;

        // Do $categories đã có toàn cục, ta chỉ cần truyền $selectedCategory, $products mới
        return view('product', [
            'selectedCategory' => $selectedCategory,
            'products' => $products
        ]);
    }

    public function detail($slug)
    {
        $product = Product::with('images')
            ->whereIn('status', [0, 1])
            ->where('slug', $slug)
            ->firstOrFail();

        $categories = Category::where('status', 1)->with('products')->get();

        // Chuẩn bị link, tiêu đề, mô tả, ảnh share
        $shareUrl = url()->current(); // url chi tiết sản phẩm hiện tại
        $shareTitle = $product->name;
        $shareDescription = \Illuminate\Support\Str::limit(strip_tags($product->description), 150);
        $shareImage = $product->image ? asset('storage/' . $product->image) : asset('default-image.jpg');

        return view('detail', compact('product', 'categories', 'shareUrl', 'shareTitle', 'shareDescription', 'shareImage'));
    }


    public function search(Request $request)
    {
        // Lấy giá trị tìm kiếm từ query string
        $query = $request->input('query');

        // Truy vấn sản phẩm có tên chứa chuỗi tìm kiếm (không phân biệt chữ hoa, chữ thường)
        // và load quan hệ comments
        $products = Product::with('comments')
            ->whereIn('status', [0, 1])
            ->whereHas('category', function ($query) {
                $query->where('status', 1);
            })
            // Tìm kiếm theo tên sản phẩm
            ->where('name', 'LIKE', '%' . $query . '%')
            ->get();

        // Lấy tất cả danh mục kèm theo sản phẩm
        $categories = Category::where('status', 1)
            ->with(['products' => function ($query) {
                $query->where('status', 1);
            }])->get();

        // Trả về view kết quả tìm kiếm kèm theo biến $products và $query
        return view('search', compact('categories', 'products', 'query'));
    }

    public function news()
    {
        // Lấy tất cả bài viết new, sắp xếp theo thời gian tạo giảm dần
        $news = News::orderBy('created_at', 'desc')->get();
        $banners = Banners::whereIn('position', [6, 7, 8])->get()->keyBy('position');
        // Trả về view about.blade.php và truyền biến $News sang
        return view('news', compact('news', 'banners'));
    }

    public function new($id)
    {
        // Lấy new hiện tại theo ID
        $new = News::findOrFail($id);

        // Lấy 2 bài viết trước (ID nhỏ hơn)
        $previousNews = News::where('id', '<', $id)
            ->orderBy('id', 'desc') // Lấy bài viết gần nhất trước đó
            ->take(2)
            ->get();

        // Lấy 2 bài viết sau (ID lớn hơn)
        $nextNews = News::where('id', '>', $id)
            ->orderBy('id', 'asc')
            ->take(2)
            ->get();

        // Trả về view và truyền cả New, previousNews và nextNews vào
        return view('new', compact('new', 'previousNews', 'nextNews'));
    }
}
