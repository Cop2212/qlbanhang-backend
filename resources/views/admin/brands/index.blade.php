@extends('admin.layouts.app')

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Quản lý hãng</h3>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
        ➕ Thêm hãng
    </a>
</div>

<div class="card shadow-sm p-4">

    <form action="{{ route('admin.brands.deleteMultiple') }}" method="POST">

        @csrf

        <button
            id="deleteSelectedBtn"
            class="btn btn-danger mb-3"
            style="display:none"
            onclick="return confirm('Xóa các hãng đã chọn?')">

            🗑 Xóa đã chọn
        </button>

        <table class="table table-bordered">

            <thead class="table-light">
                <tr>
                    <th width="40">
                        <input type="checkbox" id="checkAll">
                    </th>
                    <th width="60">STT</th>
                    <th>Tên hãng</th>
                    <th>Logo</th>
                    <th>Trạng thái</th>
                    <th width="120">Hành động</th>
                </tr>
            </thead>

            <tbody>

                @foreach($brands as $key => $brand)

                <tr>

                    <td>
                        <input type="checkbox"
                            name="ids[]"
                            value="{{ $brand->id }}"
                            class="brand-checkbox">
                    </td>

                    <td>
                        {{ $brands->firstItem() + $loop->index }}
                    </td>

                    <td>{{ $brand->name }}</td>

                    <td>
                        @if($brand->logo)
                        <img src="{{ str_replace('/upload/', '/upload/w_80,h_80,c_fill/', $brand->logo) }}" width="50">
                        @endif
                    </td>

                    <td>
                        @if($brand->is_active)
                        <span class="badge bg-success">Hiển thị</span>
                        @else
                        <span class="badge bg-danger">Ẩn</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('admin.brands.edit',$brand->id) }}"
                            class="btn btn-sm btn-warning">
                            Sửa
                        </a>
                    </td>

                </tr>

                @endforeach

            </tbody>
        </table>

        <div class="mt-3 d-flex justify-content-between align-items-center">

            <div>
                Hiển thị {{ $brands->firstItem() }} -
                {{ $brands->lastItem() }}
                / {{ $brands->total() }} hãng
            </div>

            <div>
                {{ $brands->links() }}
            </div>

        </div>

    </form>

</div>

<script>
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.brand-checkbox');
    const deleteBtn = document.getElementById('deleteSelectedBtn');

    function toggleDeleteButton() {

        let checked = document.querySelectorAll('.brand-checkbox:checked');

        deleteBtn.style.display = checked.length ? 'inline-block' : 'none';
    }

    checkAll.addEventListener('change', function() {

        checkboxes.forEach(cb => cb.checked = this.checked);

        toggleDeleteButton();
    });

    checkboxes.forEach(cb => {

        cb.addEventListener('change', toggleDeleteButton);

    });
</script>

@if(session('confirm_delete_brand'))

<form id="confirmDeleteForm"
    action="{{ route('admin.brands.deleteMultiple') }}"
    method="POST">

    @csrf

    @foreach(session('confirm_delete_brand.ids') as $id)
    <input type="hidden" name="ids[]" value="{{ $id }}">
    @endforeach

    <input type="hidden" name="confirm_delete_products" value="1">

</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        if (confirm(
                "Hãng này còn {{ session('confirm_delete_brand.count') }} sản phẩm.\n\
Bạn có chắc muốn xóa toàn bộ sản phẩm của hãng này?"
            )) {
            document.getElementById('confirmDeleteForm').submit();
        }

    });
</script>

@endif

@endsection