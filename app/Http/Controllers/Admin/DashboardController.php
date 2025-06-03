<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách năm có đơn hàng để làm filter năm
        $years = Orders::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Lấy năm được chọn, mặc định năm hiện tại
        $selectedYear = $request->get('year', Carbon::now()->year);

        // Thống kê tổng số người dùng
        $userCount = User::count();

        // Thống kê tổng số sản phẩm
        $productCount = Product::count();

        // Thống kê số đơn hàng (không tính đơn bị hủy)
        $orderCount = Orders::count();

        // Thống kê tổng doanh thu từ các đơn đã giao thành công 
        $totalRevenue = Orders::where('status', 'delivered')
            ->sum('total');

        // Tạo mảng mặc định 12 tháng với giá trị 0 cho orders và revenue
        $monthlyStats = collect(range(1, 12))->map(function ($month): array {
            return [
                'month' => $month,
                'orders' => 0,
                'revenue' => 0,
            ];
        })->toArray();
        // Lấy dữ liệu tổng số đơn và doanh thu theo tháng trong năm được chọn
        $orders = Orders::selectRaw('MONTH(created_at) as month, COUNT(*) as orders, SUM(total) as revenue')
            ->whereYear('created_at', $selectedYear)
            ->where('status',  'delivered') 
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('month')
            ->get();

        // Gán dữ liệu trả về vào mảng mặc định
        foreach ($orders as $order) {
            $monthlyStats[$order->month - 1]['orders'] = $order->orders;
            $monthlyStats[$order->month - 1]['revenue'] = $order->revenue;
        }

        $monthlyStats = collect($monthlyStats);

        // Thống kê số lượng đơn hàng theo trạng thái
$statusCounts = Orders::select('status', DB::raw('COUNT(*) as count'))
    ->groupBy('status')
    ->pluck('count', 'status')
    ->toArray();

        return view('admin.dashboard', [
            'userCount' => $userCount,
            'productCount' => $productCount,
            'orderCount' => $orderCount,
            'totalRevenue' => $totalRevenue,
            'chartData' => $monthlyStats,
            'years' => $years,
            'selectedYear' => $selectedYear,
            'statusCounts' => $statusCounts,
            
        ]);
    }
}
