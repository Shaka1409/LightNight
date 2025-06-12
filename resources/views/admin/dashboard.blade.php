@extends('layout.admin')
@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard</h1>
    

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
            @foreach($years as $year)
                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
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
            datasets: [
                {
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
        switch(status) {
            case 'pending': return 'Chờ xử lý';
            case 'processing': return 'Đang xử lý';
            case 'shipped': return 'Đang giao';
            case 'delivered': return 'Hoàn thành';
            case 'cancelled': return 'Đã hủy';
            default: return status;
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
