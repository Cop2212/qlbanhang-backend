@extends('admin.layouts.app')

@section('content')

<h2 class="mb-4">Quản lý Trader</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card card-dashboard p-3">

    <form method="GET" class="row g-2 mb-3">

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

        <!-- Ref code -->
        <div class="col-md-3">
            <label>Ref Code</label>
            <select name="ref_code" class="form-select">
                <option value="">-- Tất cả --</option>
                @foreach($refCodes as $code)
                <option value="{{ $code }}" {{ request('ref_code') == $code ? 'selected' : '' }}>
                    {{ $code }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-12">
            <button class="btn btn-primary">Lọc</button>
            <a href="{{ route('admin.traders.index') }}" class="btn btn-secondary">Reset</a>
        </div>

    </form>

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Ref Code</th>
                <th>Ngân hàng</th>
                <th>Trạng thái</th>
                <th>Số lượt tư vấn</th>
                <th width="180">Hành động</th>
            </tr>
        </thead>

        <tbody>
            @foreach($traders as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->ref_code }}</td>

                <td>
                    @if($item->profile)
                    {{ $item->profile->bank_name }} <br>
                    {{ $item->profile->bank_number }}
                    @else
                    -
                    @endif
                </td>

                <td>
                    @php $status = $item->profile->status ?? 'incomplete'; @endphp

                    @if($status === 'approved')
                    <span class="badge bg-success">Đã duyệt</span>
                    @elseif($status === 'pending')
                    <span class="badge bg-warning">Chờ duyệt</span>
                    @elseif($status === 'rejected')
                    <span class="badge bg-danger">Từ chối</span>
                    @else
                    <span class="badge bg-secondary">Chưa nhập</span>
                    @endif
                </td>

                <td>
                    {{ $item->consultations_count ?? 0 }}
                </td>

                <td>

                    @if($status !== 'approved')
                    <form method="POST" action="{{ route('admin.traders.approve', $item->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-success btn-sm">✔</button>
                    </form>
                    @endif

                    @if($status !== 'rejected')
                    <form method="POST" action="{{ route('admin.traders.reject', $item->id) }}" class="d-inline">
                        @csrf
                        <button class="btn btn-danger btn-sm">✖</button>
                    </form>
                    @endif

                    <a href="{{ route('admin.traders.show', $item->id) }}" class="btn btn-primary btn-sm">
                        👁
                    </a>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $traders->links() }}

</div>

@endsection