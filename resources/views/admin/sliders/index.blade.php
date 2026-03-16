@extends('admin.layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-3">
    <h4>Quản lý Slider</h4>
    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
        + Thêm Slider
    </a>
</div>

<div class="card">
    <div class="card-body">

        {{-- FORM DELETE MULTIPLE --}}
        <form id="deleteForm" action="{{ route('admin.sliders.deleteMultiple') }}" method="POST">
            @csrf

            <button type="submit"
                id="deleteSelectedBtn"
                class="btn btn-danger mb-3"
                style="display:none"
                onclick="return confirm('Xóa các slider đã chọn?')">
                🗑 Xóa đã chọn
            </button>

        </form>


        {{-- FORM UPDATE MULTIPLE --}}
        <form id="updateForm" action="{{ route('admin.sliders.updateMultiple') }}" method="POST">

            @csrf

            <button type="submit" class="btn btn-success mb-3">
                💾 Lưu toàn bộ
            </button>


            @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
            @endif

            <table class="table table-bordered align-middle">

                <thead>
                    <tr>
                        <th width="40">
                            <input type="checkbox" id="checkAll">
                        </th>
                        <th width="60">STT</th>
                        <th>Hình ảnh</th>
                        <th>Tiêu đề</th>
                        <th>Link</th>
                        <th width="120">Thứ tự</th>
                        <th width="150">Trạng thái</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($sliders as $slider)

                    <tr>

                        <td>
                            <input type="checkbox"
                                name="ids[]"
                                value="{{ $slider->id }}"
                                class="slider-checkbox"
                                form="deleteForm">
                        </td>

                        <td>
                            {{ $sliders->firstItem() + $loop->index }}
                        </td>

                        <td>
                            @if($slider->image)
                            <img src="{{ $slider->image }}" width="120">
                            @else
                            <span class="text-muted">Không có ảnh</span>
                            @endif
                        </td>

                        <td>{{ $slider->title }}</td>

                        <td>{{ $slider->link }}</td>

                        <td>

                            <input type="number"
                                name="sort_order[{{ $slider->id }}]"
                                value="{{ $slider->sort_order }}"
                                min="0"
                                max="{{ $maxSlider }}"
                                class="form-control"
                                style="width:90px">

                        </td>

                        <td>

                            <select name="is_active[{{ $slider->id }}]" class="form-control">

                                <option value="1" {{ $slider->is_active ? 'selected' : '' }}>
                                    Hiển thị
                                </option>

                                <option value="0" {{ !$slider->is_active ? 'selected' : '' }}>
                                    Ẩn
                                </option>

                            </select>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            <div class="mt-3 d-flex justify-content-between align-items-center">

                <div>
                    Hiển thị {{ $sliders->firstItem() }}
                    -
                    {{ $sliders->lastItem() }}
                    / {{ $sliders->total() }} slider
                </div>

                <div>
                    {{ $sliders->links() }}
                </div>

            </div>

        </form>

    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {

        const checkAll = document.getElementById('checkAll');
        const checkboxes = document.querySelectorAll('.slider-checkbox');
        const deleteBtn = document.getElementById('deleteSelectedBtn');

        function toggleDeleteButton() {
            let checked = document.querySelectorAll('.slider-checkbox:checked');
            deleteBtn.style.display = checked.length ? 'inline-block' : 'none';
        }

        checkAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleDeleteButton();
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', toggleDeleteButton);
        });

    });
</script>

@endsection