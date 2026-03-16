@extends('admin.layouts.app')

@section('content')
<h2>Danh sách tư vấn</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tên</th>
            <th>Điện thoại</th>
            <th>Email</th>
            <th>Trạng thái</th>
            <th>Thời gian</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->phone }}</td>
            <td>{{ $item->email }}</td>
            <td>
                @if($item->status == 'pending') Chưa liên hệ @endif
                @if($item->status == 'contacted') Đã liên hệ @endif
                @if($item->status == 'failed') Gọi không được @endif
            </td>
            <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
            <td><a href="{{ route('admin.consultations.show', $item->id) }}">Xem</a></td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $items->links() }}
@endsection