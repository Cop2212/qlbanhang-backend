@extends('admin.layouts.app')

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Quản lý loại sản phẩm</h3>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        ➕ Thêm loại
    </a>
</div>

<div class="card shadow-sm p-4">

    <form id="deleteForm" action="{{ route('admin.categories.deleteMultiple') }}" method="POST">
        @csrf

        <button
            id="deleteSelectedBtn"
            class="btn btn-danger mb-3"
            style="display:none">

            🗑 Xóa đã chọn
        </button>

        <table class="table table-bordered">

            <thead class="table-light">
                <tr>
                    <th width="40">
                        <input type="checkbox" id="checkAll">
                    </th>
                    <th width="60">STT</th>
                    <th>Tên loại</th>
                    <th>Slug</th>
                    <th>Trạng thái</th>
                    <th width="120">Hành động</th>
                </tr>
            </thead>

            <tbody>

                @foreach($categories as $key => $category)

                <tr>

                    <td>
                        <input type="checkbox"
                            name="ids[]"
                            value="{{ $category->id }}"
                            class="category-checkbox">
                    </td>

                    <td>
                        {{ $categories->firstItem() + $loop->index }}
                    </td>

                    <td>{{ $category->name }}</td>

                    <td>{{ $category->slug }}</td>

                    <td>
                        @if($category->is_active)
                        <span class="badge bg-success">Hiển thị</span>
                        @else
                        <span class="badge bg-danger">Ẩn</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('admin.categories.edit',$category->id) }}"
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
                Hiển thị {{ $categories->firstItem() }} -
                {{ $categories->lastItem() }}
                / {{ $categories->total() }} loại
            </div>

            <div>
                {{ $categories->links() }}
            </div>

        </div>

    </form>

</div>


<script>
    const checkAll = document.getElementById('checkAll');
    const checkboxes = document.querySelectorAll('.category-checkbox');
    const deleteBtn = document.getElementById('deleteSelectedBtn');

    function toggleDeleteButton() {

        let checked = document.querySelectorAll('.category-checkbox:checked');

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

@if(session('confirm_delete_category'))

<form id="confirmDeleteForm"
    action="{{ route('admin.categories.deleteMultiple') }}"
    method="POST">

    @csrf

    @foreach(session('confirm_delete_category.ids') as $id)
    <input type="hidden" name="ids[]" value="{{ $id }}">
    @endforeach

    <input type="hidden" name="confirm_delete_products" value="1">

</form>

<script>
    if (confirm(
            "Loại này còn {{ session('confirm_delete_category.count') }} sản phẩm.\n\
Nếu xóa, các sản phẩm sẽ KHÔNG bị xóa nhưng sẽ bị mất liên kết với loại này.\n\
Bạn có chắc muốn tiếp tục?"
        )) {
        document.getElementById('confirmDeleteForm').submit();
    }
</script>

@endif
@endsection