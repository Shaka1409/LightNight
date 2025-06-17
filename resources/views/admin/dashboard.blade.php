@extends('layout.admin')
@section('content')
    <div class="container">
        <h1 class="mb-4">Dashboard</h1>
        <p class="text-muted">Chào mừng bạn đến với trang quản trị!</p>
        <p>Trang này cung cấp cái nhìn tổng quan về các hoạt động của cửa hàng.</p>
        <div class="row">
            <div class="col-md-3">
                <a href="{{ route('user.index') }}" class="card text-decoration-none text-white bg-primary mb-3">
                    <div class="card-body text-center">
                        <i class="fa fa-users fa-2x mb-2"></i>
                        <h4>{{ $userCount }}</h4>
                        <p>Người dùng</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('product.index') }}" class="card text-decoration-none text-white bg-success mb-3">
                    <div class="card-body text-center">
                        <i class="fa fa-lightbulb fa-2x mb-2"></i>
                        <h4>{{ $productCount }}</h4>
                        <p>Sản phẩm</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.orders.index') }}" class="card text-decoration-none text-white bg-danger mb-3">
                    <div class="card-body text-center">
                        <i class="fa fa-shopping-cart fa-2x mb-2"></i>
                        <h4>{{ $orderCount }}</h4>
                        <p>Tất cả đơn hàng</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body text-center">
                        <i class="fa fa-money-bill-wave fa-2x mb-2"></i>
                        <h4>{{ number_format($totalRevenue, 0, ',', '.') }} ₫</h4>
                        <p>Tổng doanh thu</p>
                    </div>
                </div>
            </div>
        </div>

        <form method="GET" class="mb-3">
            <div class="form-inline">
                <label class="mr-2">Chọn năm:</label>
                <select name="year" onchange="this.form.submit()" class="form-control">
                    @foreach ($years as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                            {{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </form>


        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <div class="row mt-4">
            <div class="col-md-8">
                <h5 class="mb-3">Biểu đồ đơn hàng & doanh thu năm {{ $selectedYear }}</h5>
                <div class="card p-3">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <h5 class="mb-3">Tỷ lệ trạng thái đơn hàng</h5>
                <div class="card p-3">
                    <canvas id="orderStatusChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="container my-5">
    <h2 class="text-dark fw-bold mb-2">📦 Sản phẩm tồn kho thấp</h2>
    <p class="text-muted mb-4">Danh sách các sản phẩm có số lượng tồn kho <strong class="text-danger">dưới 5</strong>.</p>

    <div class="table-responsive shadow rounded overflow-hidden">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Danh mục</th>
                    <th scope="col">Sản phẩm</th>
                    <th scope="col">Tồn kho</th>
                    <th scope="col">Đơn giá</th>
                    <th scope="col">Giá sale</th>
                    <th scope="col">Ảnh</th>
                    <th scope="col">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($lowStockProducts as $index => $product)
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td>
                            {{ ucfirst($product->category->name) }}
                            @if ($product->category->status == 0)
                                <span class="badge bg-danger ms-1">Ẩn</span>
                            @endif
                        </td>
                        <td>{{ ucfirst($product->name) }}</td>
                        <td>
                            <span class="badge bg-warning text-dark">{{ $product->stock_quantity }}</span>
                        </td>
                        <td>{{ number_format($product->price, 0, ',', '.') }} ₫</td>
                        <td>
                            @if ($product->sale_price)
                                <span class="text-success">{{ number_format($product->sale_price, 0, ',', '.') }} ₫</span>
                            @else
                                <span class="text-muted fst-italic">Không giảm giá</span>
                            @endif
                        </td>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                     class="img-thumbnail shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <span class="text-muted">Chưa có ảnh</span>
                            @endif
                        </td>
                        <td>
                            @switch($product->status)
                                @case(0)
                                    <span class="badge bg-secondary">Không nổi bật</span>
                                    @break
                                @case(1)
                                    <span class="badge bg-success">Nổi bật</span>
                                    @break
                                @case(2)
                                    <span class="badge bg-dark">Ẩn</span>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            Không có sản phẩm nào có tồn kho thấp.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>



        <script>
            const chartData = @json($chartData);

            const labels = chartData.map(data => `Tháng ${data.month}`);
            const revenueData = chartData.map(data => data.revenue);
            const ordersData = chartData.map(data => data.orders);

            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Doanh thu (VNĐ)',
                            data: revenueData,
                            fill: true,
                            backgroundColor: 'rgba(75, 192, 192, 0.3)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            tension: 0.2,
                            pointRadius: 3
                        },
                        {
                            label: 'Đơn hàng',
                            data: ordersData,
                            fill: true,
                            backgroundColor: 'rgba(255, 99, 132, 0.3)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            tension: 0.2,
                            pointRadius: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN').format(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: `Biểu đồ đơn hàng & doanh thu năm {{ $selectedYear }}`
                        }
                    }
                }
            });
            const statusCounts = @json($statusCounts);

            const statusLabels = Object.keys(statusCounts).map(status => {
                switch (status) {
                    case 'pending':
                        return 'Chờ xử lý';
                    case 'processing':
                        return 'Đang xử lý';
                    case 'shipped':
                        return 'Đang giao';
                    case 'delivered':
                        return 'Hoàn thành';
                    case 'cancelled':
                        return 'Đã hủy';
                    default:
                        return status;
                }
            });

            const statusData = Object.values(statusCounts);

            const statusColors = {
                'pending': '#f0ad4e',
                'processing': '#5bc0de',
                'shipped': '#0275d8',
                'delivered': '#5cb85c',
                'cancelled': '#d9534f'
            };

            const backgroundColors = Object.keys(statusCounts).map(status => statusColors[status] || '#999');

            const ctxStatus = document.getElementById('orderStatusChart').getContext('2d');
            new Chart(ctxStatus, {
                type: 'pie',
                data: {
                    labels: statusLabels,
                    datasets: [{
                        data: statusData,
                        backgroundColor: backgroundColors
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Phân bố trạng thái đơn hàng'
                        }
                    }
                }
            });
        </script>
    </div>
@endsection
