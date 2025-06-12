@extends('layout.admin')

@section('content')
    <div class="container">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"> Sửa {{ $banner->name }} </h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Tên bài viết -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên banner *</label>
                        <input type="text" id="name" name="name" required value="{{ old('name', $banner->name) }}"
                            class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                     <!-- Ảnh hiện tại -->
                <div class="mb-3">
                    <label class="form-label">Ảnh hiện tại</label>
                    <div>
                        <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->name }}" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                </div>
<!-- Ảnh mới -->
                <div class="mb-3">
                    <label for="image" class="form-label">Ảnh mới</label>
                    <input type="file" id="image" name="image" accept="image/*"
                           class="form-control @error('image') is-invalid @enderror" onchange="previewNewImage(event)">
                    <div class="mt-3">
                        <img id="newImagePreview" class="img-thumbnail d-none" width="100%">
                    </div>
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                    <!-- số thứ tự -->
                    <div class="mb-3">
                        <label for="position" class="form-label">Thứ tự banner *</label>
                        <input type="number" id="position" name="position" required readonly value="{{ old('position', $banner->position) }}"
                            class="form-control @error('position') is-invalid @enderror">
                        @error('position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mô tả -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả *</label>
                        <textarea id="description" name="description" rows="3" required
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $banner->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nút lưu -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Quay lại</a>
                        <button type="submit" class="btn btn-success">Lưu banner</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script xem trước ảnh -->
    <script>
        function previewImage(event) {
            let reader = new FileReader();
            reader.onload = function() {
                let output = document.getElementById('imagePreview');
                output.src = reader.result;
                output.classList.remove('d-none');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
