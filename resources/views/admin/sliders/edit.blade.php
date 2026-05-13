@extends('admin.layouts.app')

@section('content')

<div class="card">

    <div class="card-header">
        <h4>Chỉnh sửa Slider</h4>
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
            action="{{ route('admin.sliders.update', $slider->id) }}"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tiêu đề</label>
                <input type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title', $slider->title) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Hình ảnh</label>
                <input type="file"
                    name="image"
                    class="form-control"
                    accept="image/*"
                    onchange="previewImage(event)">

                <div class="mt-2">
                    <p class="small text-muted mb-1">Ảnh hiện tại:</p>
                    <img id="preview"
                        src="{{ $slider->image }}"
                        style="max-width:300px; border-radius: 8px; display: {{ $slider->image ? 'block' : 'none' }};">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Link</label>
                <input type="text"
                    name="link"
                    class="form-control"
                    value="{{ old('link', $slider->link) }}"
                    placeholder="https://example.com">
            </div>

            <div class="mb-3">
                <label class="form-label">Thứ tự hiển thị (Tối đa: {{ $maxSlider }})</label>
                <input type="number"
                    name="sort_order"
                    class="form-control"
                    value="{{ old('sort_order', $slider->sort_order) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="is_active" class="form-control">
                    <option value="1" {{ old('is_active', $slider->is_active) == 1 ? 'selected' : '' }}>
                        Hiển thị
                    </option>
                    <option value="0" {{ old('is_active', $slider->is_active) == 0 ? 'selected' : '' }}>
                        Ẩn
                    </option>
                </select>
            </div>

            <button class="btn btn-primary">
                Lưu thay đổi
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
