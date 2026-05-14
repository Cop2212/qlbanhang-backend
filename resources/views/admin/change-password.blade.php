@extends('admin.layouts.app')

@section('content')

<h3 class="mb-4">Đổi mật khẩu</h3>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm p-4">

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.update-password') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Mật khẩu mới</label>
                    <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                    @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Xác nhận mật khẩu mới</label>
                    <input type="password" name="new_password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            </form>

        </div>
    </div>
</div>

@endsection
