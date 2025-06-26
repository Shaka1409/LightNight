<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
    public function __construct()
    {
        // Tất cả các hành động đều yêu cầu người dùng phải đăng nhập
        $this->middleware('auth');
        // Chỉ admin mới có quyền truy cập index (quản lý bình luận)
        $this->middleware('admin')->only('index');
    }

    /**
     * Hiển thị danh sách bình luận cho admin quản lý.
     */
    public function index(Request $request)
    {
        $query = Comment::query();

        if ($request->filled('q')) {
            $keyword = $request->q;

            $query->where('comment', 'like', "%$keyword%")
                ->orWhereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                })
                ->orWhereHas('product', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%$keyword%");
                });
        }

        if ($request->filled('status') || $request->status === '0') {
            $query->where('status', $request->status);
        }


        $comments = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.comments.index', compact('comments'));
    }



    /**
     * Lưu bình luận mới (cho người dùng đăng nhập gửi bình luận).
     */
    public function store(CommentRequest $request)
    {
        $userId = Auth::id();
        $cacheKey = 'comment_throttle_user_' . $userId;

        // Nếu user đã bình luận trong 1 phút gần đây
        if (Cache::has($cacheKey)) {
            return redirect()->back()->withErrors(['content' => 'Bạn cần chờ 1 phút trước khi gửi bình luận tiếp theo.']);
        }

        // Validate xong, lấy dữ liệu đã xác thực
        $data = $request->validated();
        $data['user_id'] = $userId;

        Comment::create($data);

        // Đặt throttle trong 1 phút
        Cache::put($cacheKey, true, now()->addMinutes(5));

        return redirect()->back()->with('success', 'Gửi bình luận thành công, chờ phê duyệt.');
    }

    public function updateStatus(Request $request, $comment)
    {
        $comment = Comment::findOrFail($comment);
        $comment->status = $request->input('status');
        $comment->save();

        return redirect()->route('admin.comments.index')->with('success', 'Cập nhật trạng thái thành công!');
    }
    /**
     * Xoá bình luận.
     *
     * Cho phép xoá nếu người dùng đang đăng nhập là chủ sở hữu bình luận
     * hoặc nếu người dùng là admin.
     */
    public function destroy(Comment $comment)
    {
        // Kiểm tra quyền: nếu không phải chủ sở hữu bình luận và không phải admin
        if (Auth::id() !== $comment->user_id && !Auth::user()->role === 'admin') {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa bình luận này.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Bình luận đã được xóa.');
    }
}
