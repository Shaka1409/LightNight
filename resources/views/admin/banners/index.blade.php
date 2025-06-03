@extends('layout.admin')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">Quản lý Banner</h1>

            <a class="btn btn-primary mb-3" href="{{ route('banners.create') }}" role="button">Thêm Banner mới</a>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tên Banner</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Thứ tự</th>
                            <th scope="col">Mô tả</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banners as $key => $banner)
                            <tr>
                                <th scope="row">{{ $key + 1 }}</th>
                                <td>{{ $banner->name }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->name }}" width="100">
                                </td>
                                <td>{{ $banner->position}}</td>
                                <td>{{ $banner->description }}</td>
                                <td>
                                    <a class="btn btn-warning" href="{{ route('banners.edit', $banner->id) }}">Sửa</a>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Hiển thị các nút phân trang -->
                <div class="d-flex justify-content-center">
                    {{ $banners->links('pagination::bootstrap-5')}}
                </div>
            </div>
    </div>
@endsection
