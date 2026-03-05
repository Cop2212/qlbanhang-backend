@extends('admin.layouts.app')

@section('content')

<h3 class="mb-4">Thêm sản phẩm</h3>

<div class="card shadow-sm p-4">

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Tên sản phẩm</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>SKU</label>
                <input type="text" name="sku" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Danh mục</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Chọn danh mục</option>

                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Thương hiệu</label>
                <select name="brand_id" class="form-select" required>

                    <option value="">Chọn thương hiệu</option>

                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">
                        {{ $brand->name }}
                    </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label>Mô tả ngắn</label>
                <textarea name="short_description" class="form-control"></textarea>
            </div>

            <div class="col-md-12 mb-3">
                <label>Mô tả chi tiết</label>
                <textarea name="description" class="form-control" rows="5"></textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>Ảnh đại diện</label>
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
            </div>

            <div class="col-md-12 mb-3">

                <label>Ảnh chi tiết</label>

                <div class="d-flex gap-2">

                    <input type="file"
                        id="image-input"
                        name="images[]"
                        class="form-control"
                        accept="image/*"
                        multiple
                        style="display:none">

                    <button type="button"
                        class="btn btn-success"
                        onclick="document.getElementById('image-input').click()">

                        ➕ Thêm ảnh

                    </button>

                </div>

            </div>

            <div class="row mt-3" id="preview-images"></div>

            <div class="col-md-4 mb-3">
                <label>Giá</label>
                <input type="number" name="price" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Giá khuyến mãi</label>
                <input type="number" name="sale_price" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label>Tồn kho</label>
                <input type="number" name="stock" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label>Trạng thái</label>
                <select name="is_active" class="form-select">
                    <option value="1">Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>

        </div>

        <button type="submit" class="btn btn-primary mt-3">
            Lưu sản phẩm
        </button>

    </form>

    <script>
        const imageInput = document.getElementById('image-input');
        const previewContainer = document.getElementById('preview-images');

        let fileStore = new DataTransfer();

        imageInput.addEventListener('change', function() {

            const files = this.files;

            for (let i = 0; i < files.length; i++) {

                const file = files[i];

                fileStore.items.add(file);

                const reader = new FileReader();

                reader.onload = function(e) {

                    const col = document.createElement('div');
                    col.classList.add('col-md-2', 'mb-3');

                    col.innerHTML = `
            <div class="position-relative">

                <img src="${e.target.result}"
                     class="img-fluid rounded border">

                <button type="button"
                    class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-image">
                    ✕
                </button>

            </div>
            `;

                    previewContainer.appendChild(col);

                    const removeBtn = col.querySelector('.remove-image');

                    removeBtn.onclick = function() {

                        const index = [...previewContainer.children].indexOf(col);

                        fileStore.items.remove(index);

                        imageInput.files = fileStore.files;

                        col.remove();
                    };

                };

                reader.readAsDataURL(file);
            }

            imageInput.files = fileStore.files;

        });
    </script>

</div>

@endsection