@extends('layout.admin')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-warning text-white">
            <h4 class="mb-0">✏️ Chỉnh Sửa Tin Tức</h4>
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

            <form action="{{ route('news.update', $new->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Tên tin tức -->
                <div class="mb-3">
                    <label for="name" class="form-label">Tên tin tức *</label>
                    <input type="text" id="name" name="name" required value="{{ old('name', $new->name) }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Link -->
                <div class="mb-3">
                    <label for="link" class="form-label">Link tin tức</label>
                    <input type="url" id="link" name="link" value="{{ old('link', $new->link) }}"
                           class="form-control @error('link') is-invalid @enderror">
                    @error('link') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Ảnh hiện tại -->
                <div class="mb-3">
                    <label class="form-label">Ảnh hiện tại</label>
                    <div>
                        <img src="{{ asset('storage/' . $new->image) }}" alt="{{ $new->name }}" class="img-thumbnail" style="max-width: 200px;">
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

                <!-- Mô tả -->
                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả *</label>
                    <textarea id="description" name="description" rows="3" required
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $new->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Nội dung -->
                <div class="mb-3">
                    <label for="content" class="form-label">Nội dung *</label>
                    <textarea id="content" name="content" rows="5" required
                              class="form-control @error('content') is-invalid @enderror">{{ old('content', $new->content) }}</textarea>
                    @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Trạng thái -->
                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái *</label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="1" {{ old('status', $new->status) == 1 ? 'selected' : '' }}>Nổi bật</option>
                        <option value="0" {{ old('status', $new->status) == 0 ? 'selected' : '' }}>Không nổi bật</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Nút lưu -->
                <div class="d-flex justify-content-between">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Quay lại</a>
                    <button type="submit" class="btn btn-success">Cập nhật tin tức</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script xem trước ảnh -->
<script>
    function previewNewImage(event) {
        let reader = new FileReader();
        reader.onload = function(){
            let output = document.getElementById('newImagePreview');
            output.src = reader.result;
            output.classList.remove('d-none');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
