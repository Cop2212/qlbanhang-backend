@extends('admin.layouts.app')

@section('content')

<div class="card">

    <div class="card-header">
        <h4>Thêm Slider</h4>
    </div>

    <div class="card-body">

        {{-- Hiển thị lỗi --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST"
            action="{{ route('admin.sliders.store') }}"
            enctype="multipart/form-data">

            @csrf

            <div class="mb-3">
                <label class="form-label">Tiêu đề</label>
                <input type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Hình ảnh</label>
                <input type="file"
                    name="image"
                    class="form-control"
                    accept="image/*"
                    onchange="previewImage(event)">

                <img id="preview"
                    style="margin-top:10px; max-width:300px; display:none;">
            </div>

            <div class="mb-3">
                <label class="form-label">Link</label>
                <input type="text"
                    name="link"
                    class="form-control"
                    value="{{ old('link') }}"
                    placeholder="https://example.com">
            </div>

            <div class="mb-3">
                <label class="form-label">Thứ tự hiển thị</label>
                <input type="number"
                    name="sort_order"
                    class="form-control"
                    value="{{ old('sort_order',0) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>
                        Hiển thị
                    </option>
                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>
                        Ẩn
                    </option>
                </select>
            </div>

            <button class="btn btn-primary">
                Lưu Slider
            </button>

            <a href="{{ route('admin.sliders.index') }}"
                class="btn btn-secondary">
                Quay lại
            </a>

        </form>

    </div>

</div>

<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview');

        if (input.files && input.files[0]) {
            preview.src = URL.createObjectURL(input.files[0]);
            preview.style.display = "block";
        }
    }
</script>



@endsection