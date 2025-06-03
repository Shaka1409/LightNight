@extends('layout.admin')
@section('content')
    <div class="container">
        <div class="card">
            <h1 class="card-header">Chỉnh Sửa Danh Mục</h1>
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

                <form action="{{ route('category.update', $category->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên danh mục</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ $category->name }}" placeholder="Nhập tên danh mục" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select name="status" id="status" class="form-control">
                            <option value="1" {{ $category->status == 1 ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ $category->status == 0 ? 'selected' : '' }}>Ẩn</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <input type="text" name="description" id="description" class="form-control"
                            value="{{ $category->description }}" placeholder="Nhập mô tả">
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
