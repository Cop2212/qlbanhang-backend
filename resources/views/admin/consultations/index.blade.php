@extends('admin.layouts.app')

@section('content')
<h2>Danh sách tư vấn</h2>

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<form method="GET" action="{{ route('admin.consultations.index') }}" class="row g-2 mb-3">
    <!-- Số điện thoại -->
    <div class="col-md-3">
        <label>Số điện thoại</label>
        <select name="phone" class="form-select">
            <option value="">-- Tất cả --</option>
            @foreach($phones as $phone)
            <option value="{{ $phone }}" {{ request('phone') == $phone ? 'selected' : '' }}>
                {{ $phone }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- Email -->
    <div class="col-md-3">
        <label>Email</label>
        <select name="email" class="form-select">
            <option value="">-- Tất cả --</option>
            @foreach($emails as $email)
            <option value="{{ $email }}" {{ request('email') == $email ? 'selected' : '' }}>
                {{ $email }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label>Affiliate</label>
        <select name="trader_id" class="form-select">
            <option value="">-- Tất cả --</option>
            @foreach($traders as $trader)
            <option value="{{ $trader->id }}" {{ request('trader_id') == $trader->id ? 'selected' : '' }}>
                {{ $trader->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label>Sản phẩm</label>
        <select name="product_id" class="form-select">
            <option value="">-- Tất cả --</option>
            @foreach($products as $product)
            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                {{ $product->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label>Ref code</label>
        <select name="ref_code" class="form-select">
            <option value="">-- Tất cả --</option>
            @foreach($refCodes as $code)
            <option value="{{ $code }}" {{ request('ref_code') == $code ? 'selected' : '' }}>
                {{ $code }}
            </option>
            @endforeach
        </select>
    </div>

    <!-- Trạng thái -->
    <div class="col-md-2">
        <label>Trạng thái</label>
        <select name="status" class="form-select">
            <option value="">-- Tất cả --</option>
            <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Chưa liên hệ</option>
            <option value="contacted" {{ request('status')=='contacted' ? 'selected' : '' }}>Đã liên hệ</option>
            <option value="failed" {{ request('status')=='failed' ? 'selected' : '' }}>Gọi không được</option>
        </select>
    </div>

    <!-- Từ ngày -->
    <div class="col-md-2">
        <label>Từ ngày</label>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
    </div>

    <!-- Đến ngày -->
    <div class="col-md-2">
        <label>Đến ngày</label>
        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
    </div>

    <!-- Buttons -->
    <div class="col-md-12 mt-2">
        <button type="submit" class="btn btn-primary me-2">Lọc</button>
        <a href="{{ route('admin.consultations.index') }}" class="btn btn-secondary">Clear</a>
    </div>
</form>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Tên</th>
            <th>Điện thoại</th>
            <th>Email</th>
            <th>Sản phẩm</th>
            <th>Affiliate</th>
            <th>Ref code</th>
            <th>Trạng thái</th>
            <th>Thời gian</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <!-- Dòng chính -->
        <tr data-bs-toggle="collapse" data-bs-target="#detail-{{ $item->id }}" style="cursor:pointer;">
            <td>{{ $item->name }}</td>
            <td>{{ $item->phone }}</td>
            <td>{{ $item->email }}</td>
            <td>
                {{ $item->product->name ?? 'N/A' }}
            </td>

            <td>
                {{ $item->trader->name ?? 'N/A' }}
            </td>

            <td>
                {{ $item->ref_code ?? 'N/A' }}
            </td>
            <td>
                @if($item->status == 'pending')
                <span class="badge bg-warning">Chưa liên hệ</span>
                @elseif($item->status == 'contacted')
                <span class="badge bg-success">Đã liên hệ</span>
                @elseif($item->status == 'failed')
                <span class="badge bg-danger">Gọi không được</span>
                @endif
            </td>
            <td>{{ $item->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') }}</td>
            <td>Click để xem chi tiết</td>
        </tr>

        <!-- Dòng chi tiết collapse -->
        <tr class="collapse" id="detail-{{ $item->id }}">
            <td colspan="9">
                <div class="p-3 bg-light">

                    <!-- Nội dung khách gửi readonly -->
                    <div class="mb-2">
                        <label><strong>Nội dung khách gửi:</strong></label>
                        <textarea class="form-control" rows="3" readonly>{{ $item->message }}</textarea>
                    </div>

                    <!-- Form admin thêm/sửa ghi chú -->
                    <form action="{{ route('admin.consultations.updateAdminMessage', $item->id) }}" method="POST" class="d-inline-block me-1">
                        @csrf
                        @method('PATCH')

                        <div class="mb-2">
                            <label><strong>Ghi chú / Nội dung admin:</strong></label>
                            <textarea name="message_admin" class="form-control" rows="3">{{ $item->message_admin }}</textarea>
                        </div>

                        <div class="mb-2">
                            <label><strong>Trạng thái:</strong></label><br>
                            <select name="status" class="form-select w-auto d-inline" data-id="{{ $item->id }}">
                                <option value="pending" {{ $item->status=='pending' ? 'selected' : '' }}>Chưa liên hệ</option>
                                <option value="contacted" {{ $item->status=='contacted' ? 'selected' : '' }}>Đã liên hệ</option>
                                <option value="failed" {{ $item->status=='failed' ? 'selected' : '' }}>Gọi không được</option>
                            </select>
                        </div>
                        <div class="mb-2" id="result-wrapper-{{ $item->id }}" style="display: none;">
                            <label><strong>Kết quả:</strong></label><br>
                            <select name="result" class="form-select w-auto d-inline">
                                <option value="">-- Chọn kết quả --</option>
                                <option value="thinking" {{ $item->result=='thinking' ? 'selected' : '' }}>Đang suy nghĩ</option>
                                <option value="not_bought" {{ $item->result=='not_bought' ? 'selected' : '' }}>Không mua</option>
                                <option value="bought" {{ $item->result=='bought' ? 'selected' : '' }}>Đã mua</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm">Lưu / Cập nhật</button>
                    </form>

                    <!-- Form xóa -->
                    <form action="{{ route('admin.consultations.destroy', $item->id) }}" method="POST" class="d-inline-block ms-1" onsubmit="return confirm('Bạn có chắc muốn xóa tư vấn này không?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger btn-sm">Xóa</button>
                    </form>

                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $items->links() }}
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('select[name="status"]').forEach(function(select) {

            const id = select.getAttribute('data-id');
            const resultWrapper = document.getElementById('result-wrapper-' + id);

            function toggleResult() {
                if (!resultWrapper) return; // ✅ tránh lỗi null

                if (select.value === 'contacted') {
                    resultWrapper.style.display = 'block';
                } else {
                    resultWrapper.style.display = 'none';
                }
            }

            // init
            toggleResult();

            // on change
            select.addEventListener('change', toggleResult);
        });
    });
</script>