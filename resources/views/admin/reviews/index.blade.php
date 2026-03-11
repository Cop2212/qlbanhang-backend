@extends('admin.layouts.app')

@section('content')

<h4 class="mb-3">Quản lý đánh giá sản phẩm</h4>

<div class="card">
<div class="card-body">

<table class="table table-bordered">

<thead>
<tr>
<th>ID</th>
<th>Sản phẩm</th>
<th>Khách</th>
<th>Rating</th>
<th>Bình luận</th>
<th>Trạng thái</th>
<th width="150">Hành động</th>
</tr>
</thead>

<tbody>

@foreach($reviews as $review)

<tr>

<td>{{ $review->id }}</td>

<td>{{ $review->product->name }}</td>

<td>
{{ $review->name }} <br>
<small>{{ $review->email }}</small>
</td>

<td>
⭐ {{ $review->rating }}/5
</td>

<td>{{ $review->comment }}</td>

<td>
@if($review->is_approved)
<span class="badge bg-success">Đã duyệt</span>
@else
<span class="badge bg-warning">Chờ duyệt</span>
@endif
</td>

<td>

<form method="POST"
action="{{ route('admin.reviews.approve',$review->id) }}"
style="display:inline">
@csrf
<button class="btn btn-success btn-sm">
Duyệt
</button>
</form>

<form method="POST"
action="{{ route('admin.reviews.destroy',$review->id) }}"
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

@endsection