@extends('admin.layout')

@section('content')
<h2>Chi tiết yêu cầu tư vấn</h2>

<p><strong>Tên:</strong> {{ $item->name }}</p>
<p><strong>Số điện thoại:</strong> {{ $item->phone }}</p>
<p><strong>Email:</strong> {{ $item->email }}</p>
<p><strong>Nội dung:</strong> {{ $item->message }}</p>

<form method="POST" action="{{ route('admin.consultations.status', $item->id) }}">
    @csrf

    <label>Trạng thái:</label>
    <select name="status">
        <option value="pending" @selected($item->status=='pending')>Chưa liên hệ</option>
        <option value="contacted" @selected($item->status=='contacted')>Đã liên hệ</option>
        <option value="failed" @selected($item->status=='failed')>Không liên lạc được</option>
    </select>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>

@endsection