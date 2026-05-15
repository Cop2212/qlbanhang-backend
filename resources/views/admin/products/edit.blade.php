@extends('admin.layouts.app')

@section('content')

<h3 class="mb-4">Sửa sản phẩm</h3>

<form action="{{ route('admin.products.update',$product->id) }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="card p-4">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Tên sản phẩm</label>
                <input type="text"
                    name="name"
                    class="form-control"
                    value="{{ $product->name }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>SKU</label>
                <input type="text"
                    name="sku"
                    class="form-control"
                    value="{{ $product->sku }}">
            </div>

            <div class="col-md-6 mb-3">

                <label>Thumbnail</label>

                @if($product->thumbnail)
                <div class="mb-2 position-relative">

                    <img src="{{ $product->thumbnail }}"
                        width="120"
                        class="border rounded">

                    <label class="text-danger d-block mt-1">
                        <input type="checkbox" name="delete_thumbnail" value="1">
                        Xóa ảnh hiện tại
                    </label>

                </div>
                @endif

                <input type="file"
                    name="thumbnail"
                    class="form-control"
                    onchange="previewThumbnail(event)">

                <img id="thumbnailPreview"
                    style="margin-top:10px;max-width:120px;display:none;">

            </div>

            @if($product->images->count())

            <div class="row mt-3">

                @foreach($product->images as $img)

                <div class="col-md-2 text-center mb-3">

                    <img src="{{ $img->image_path }}"
                        class="img-fluid border rounded">

                    <label class="text-danger">
                        <input type="checkbox"
                            name="delete_images[]"
                            value="{{ $img->id }}">
                        Xóa
                    </label>

                </div>

                @endforeach

            </div>

            @endif


            <div class="col-md-6 mb-3">
                <label class="form-label d-block">Danh mục</label>
                @php
                $selectedCategories = $product->categories->pluck('id')->toArray();
                @endphp
                
                <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto; background-color: #f8f9fa;">
                    @foreach($categories as $category)
                    <div class="form-check mb-2">
                        <input class="form-check-input" 
                               type="checkbox" 
                               name="category_ids[]" 
                               value="{{ $category->id }}" 
                               id="category_{{ $category->id }}"
                               {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                        <label class="form-check-label" for="category_{{ $category->id }}">
                            {{ $category->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('category_ids')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label>Thương hiệu</label>
                <select name="brand_id" class="form-select">

                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}"
                        {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                    @endforeach

                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label>Giá</label>
                <input type="number"
                    name="price"
                    class="form-control"
                    value="{{ $product->price }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Giá khuyến mãi</label>
                <input type="number"
                    name="sale_price"
                    class="form-control"
                    value="{{ $product->sale_price }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Tồn kho</label>
                <input type="number"
                    name="stock"
                    class="form-control"
                    value="{{ $product->stock }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Màu sắc</label>
                <input type="text"
                    name="color"
                    class="form-control"
                    value="{{ $product->color }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Trạng thái</label>

                <select name="is_active" class="form-select">
                    <option value="1" {{ $product->is_active ? 'selected' : '' }}>
                        Hiển thị
                    </option>

                    <option value="0" {{ !$product->is_active ? 'selected' : '' }}>
                        Ẩn
                    </option>
                </select>

            </div>

            <div class="col-md-12 mb-3">
                <label>Mô tả ngắn</label>
                <textarea name="short_description"
                    class="form-control"
                    rows="2">{{ $product->short_description }}</textarea>
            </div>

            <div class="col-md-12 mb-3">
                <label>Mô tả chi tiết</label>
                <textarea name="description"
                    class="form-control"
                    rows="4">{{ $product->description }}</textarea>
            </div>

        </div>

        <button class="btn btn-success">
            💾 Cập nhật sản phẩm
        </button>

    </div>

</form>

<script>
    function previewThumbnail(event) {
        const preview = document.getElementById('thumbnailPreview');

        preview.src = URL.createObjectURL(event.target.files[0]);
        preview.style.display = "block";
    }
</script>

@endsection