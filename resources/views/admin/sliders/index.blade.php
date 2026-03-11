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

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th width="60">ID</th>
                    <th>Hình ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Link</th>
                    <th width="120">Thứ tự</th>
                    <th width="150">Trạng thái</th>
                    <th width="180">Hành động</th>
                </tr>
            </thead>

            <tbody>

                @foreach($sliders as $slider)

                <tr>

                    <form action="{{ route('admin.sliders.update',$slider->id) }}" method="POST">

                        @csrf
                        @method('PUT')

                        <td>{{ $slider->id }}</td>

                        <td>
                        <td>
                            @if($slider->image)
                            <img src="{{ $slider->image }}" width="120">
                            @else
                            <span class="text-muted">Không có ảnh</span>
                            @endif
                        </td>
                        </td>

                        <td>{{ $slider->title }}</td>

                        <td>{{ $slider->link }}</td>

                        <td>
                            <input type="number"
                                name="sort_order"
                                value="{{ $slider->sort_order }}"
                                min="1"
                                max="{{ $maxSlider }}"
                                class="form-control">
                        </td>

                        <td>
                            <select name="is_active" class="form-control">
                                <option value="1" {{ $slider->is_active ? 'selected' : '' }}>
                                    Hiển thị
                                </option>

                                <option value="0" {{ !$slider->is_active ? 'selected' : '' }}>
                                    Ẩn
                                </option>
                            </select>
                        </td>

                        <td>

                            <button class="btn btn-success btn-sm">
                                Lưu
                            </button>

                            <a href="{{ route('admin.sliders.edit',$slider->id) }}"
                                class="btn btn-warning btn-sm">
                                Sửa
                            </a>

                    </form>

                    <form action="{{ route('admin.sliders.destroy',$slider->id) }}"
                        method="POST"
                        style="display:inline">
                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm">
                            Xóa
                        </button>

                    </form>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>
</div>

@if($errors->any())
<div class="alert alert-danger">
    {{ $errors->first() }}
</div>
@endif

@endsection