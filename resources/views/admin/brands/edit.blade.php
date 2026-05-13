@extends('admin.layouts.app')

@section('content')

<h3 class="mb-4">Chỉnh sửa hãng</h3>

<div class="card shadow-sm p-4">

    <form method="POST" action="{{ route('admin.brands.update', $brand->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Tên hãng</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name) }}">
        </div>

        <div class="mb-3">
            <label>Logo</label>
            <input type="file" name="logo" class="form-control" id="logoInput">

            <!-- Ảnh hiện tại hoặc preview -->
            <div class="mt-3">
                @if($brand->logo)
                    <p class="small text-muted">Logo hiện tại:</p>
                    <img id="logoPreview" src="{{ $brand->logo }}" width="120" style="border-radius:8px; display:block;">
                @else
                    <img id="logoPreview" src="" width="120" style="display:none; border-radius:8px;">
                @endif
            </div>
        </div>

        <div class="mb-3">
            <label>Trạng thái</label>
            <select name="is_active" class="form-select">
                <option value="1" {{ $brand->is_active == 1 ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ $brand->is_active == 0 ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>

        <button class="btn btn-primary">Lưu thay đổi</button>

    </form>

</div>


<script>
    document.getElementById('logoInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('logoPreview');

        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    });
</script>

@endsection
