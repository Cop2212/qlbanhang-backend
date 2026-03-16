@extends('admin.layouts.app')

@section('content')

<h4 class="mb-3">Quản lý đánh giá sản phẩm</h4>

<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-hover align-middle">

            <thead class="table-light">
                <tr>
                    <th width="60">STT</th>
                    <th>Sản phẩm</th>
                    <th>Khách</th>
                    <th width="120">Rating</th>
                    <th>Bình luận</th>
                    <th width="120">Ngày</th>
                    <th width="100">Trạng thái</th>
                    <th width="180">Hành động</th>
                </tr>
            </thead>

            <tbody>

                @foreach($reviews as $review)

                <tr>

                    <td>
                        {{ ($reviews->currentPage() - 1) * $reviews->perPage() + $loop->iteration }}
                    </td>

                    <td>
                        <strong>{{ $review->product->name }}</strong>
                    </td>

                    <td>
                        {{ $review->name }} <br>
                        <small class="text-muted">{{ $review->email }}</small>
                    </td>

                    <td>

                        @for($i = 1; $i <= 5; $i++)
                            @if($i <=$review->rating)
                            <span style="color:#f5a623;">★</span>
                            @else
                            <span style="color:#ccc;">☆</span>
                            @endif
                            @endfor

                    </td>

                    <td style="max-width:300px">
                        {{ Str::limit($review->comment,120) }}
                    </td>

                    <td>
                        {{ $review->created_at->format('d/m/Y') }}
                    </td>

                    <td>

                        @if($review->is_approved)

                        <span class="badge bg-success">
                            Hiển thị
                        </span>

                        @else

                        <span class="badge bg-secondary">
                            Ẩn
                        </span>

                        @endif

                    </td>

                    <td>

                        <!-- Toggle hiển thị -->

                        <form method="POST"
                            action="{{ route('admin.reviews.approve',$review->id) }}"
                            style="display:inline">

                            @csrf
                            @method('PATCH')

                            <button class="btn btn-warning btn-sm">
                                {{ $review->is_approved ? 'Ẩn' : 'Hiện' }}
                            </button>

                        </form>

                        <!-- Xóa -->

                        <form method="POST"
                            action="{{ route('admin.reviews.destroy',$review->id) }}"
                            style="display:inline"
                            onsubmit="return confirm('Xóa đánh giá này?')">

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

        <!-- Pagination -->

        <div class="mt-3">
            {{ $reviews->links() }}
        </div>

    </div>
</div>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@endsection