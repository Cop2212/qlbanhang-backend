@extends('admin.layouts.app')

@section('content')

<h2 class="mb-4">Chi tiết Trader</h2>

<div class="card p-4 mb-3">

    <h4>👤 Thông tin tài khoản</h4>
    <p><b>ID:</b> {{ $trader->id }}</p>
    <p><b>Tên:</b> {{ $trader->name }}</p>
    <p><b>Email:</b> {{ $trader->email }}</p>
    <p><b>Ref Code:</b> {{ $trader->ref_code }}</p>

</div>

<div class="card p-4 mb-3">

    <h4>🏦 Thông tin ngân hàng</h4>

    @if($trader->profile)
    <p><b>Ngân hàng:</b> {{ $trader->profile->bank_name }}</p>
    <p><b>Số TK:</b> {{ $trader->profile->bank_number }}</p>
    <p><b>Chủ TK:</b> {{ $trader->profile->bank_owner }}</p>
    <p><b>Phone:</b> {{ $trader->profile->phone }}</p>
    @else
    <p>Chưa cập nhật</p>
    @endif

</div>

<div class="card p-4 mb-3">

    <h4>📊 Trạng thái</h4>

    @php $status = $trader->profile->status ?? 'incomplete'; @endphp

    @if($status === 'approved')
    <span class="badge bg-success">Đã duyệt</span>
    @elseif($status === 'pending')
    <span class="badge bg-warning">Chờ duyệt</span>
    @elseif($status === 'rejected')
    <span class="badge bg-danger">Từ chối</span>
    @else
    <span class="badge bg-secondary">Chưa nhập</span>
    @endif

</div>

<div class="card p-4">

    <h4>⚙ Hành động</h4>

    @if($status !== 'approved')
    <form method="POST" action="{{ route('admin.traders.approve', $trader->id) }}" class="d-inline">
        @csrf
        <button class="btn btn-success">✔ Duyệt</button>
    </form>
    @endif

    @if($status !== 'rejected')
    <form method="POST" action="{{ route('admin.traders.reject', $trader->id) }}" class="d-inline">
        @csrf
        <button class="btn btn-danger">✖ Từ chối</button>
    </form>
    @endif

</div>

<a href="{{ route('admin.traders.index') }}" class="btn btn-secondary mt-3">
    ← Quay lại
</a>

@endsection