@extends('layout.admin')
@section('content')
    <div class="container">
        <div class="card">
            <h1 class="card-header">Thêm Danh Mục</h1>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="row g-3" action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data"
                    novalidate>
                    @csrf
                    <div class="col-md-4">
                        <label for="name" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Nhập tên danh mục" required>
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1">Hiển thị</option>
                            <option value="0">Ẩn</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="description" class="form-label">Mô tả</label>
                        <input type="text" class="form-control" id="description" name="description"
                            placeholder="Nhập mô tả">
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
