@extends('admin.layouts.app')

@section('content')

<h3 class="mb-4">Thêm hãng</h3>

<div class="card shadow-sm p-4">

    <form method="POST" action="{{ route('admin.brands.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Tên hãng</label>
            <input type="text" name="name" class="form-control">
        </div>

        <div class="mb-3">
            <label>Logo</label>
            <input type="file" name="logo" class="form-control" id="logoInput">

            <!-- Ảnh preview -->
            <div class="mt-3">
                <img id="logoPreview" src="" width="120" style="display:none; border-radius:8px;">
            </div>
        </div>

        <div class="mb-3">
            <label>Trạng thái</label>
            <select name="is_active" class="form-select">
                <option value="1">Hiển thị</option>
                <option value="0">Ẩn</option>
            </select>
        </div>

        <button class="btn btn-primary">Lưu</button>

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