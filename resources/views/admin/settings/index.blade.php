@extends('admin.layouts.app')

@section('content')

<h4 class="mb-4">Cài đặt website</h4>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">

    @csrf

    <div class="card">
        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Tên website</label>
                    <input type="text" name="site_name"
                        class="form-control"
                        value="{{ $setting->site_name }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="text" name="email"
                        class="form-control"
                        value="{{ $setting->email }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone"
                        class="form-control"
                        value="{{ $setting->phone }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Logo</label>

                    <input type="file"
                        name="logo"
                        class="form-control"
                        onchange="previewLogo(event)">

                    <br>

                    <img id="logoPreview"
                        src="{{ $setting->logo }}"
                        width="120"
                        class="mt-2">

                </div>

                <div class="col-md-4 mb-3">
                    <label>Số slider hiển thị</label>

                    <input type="number"
                        name="max_sliders"
                        class="form-control"
                        value="{{ $setting->max_sliders }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label>Địa chỉ</label>
                    <input type="text" name="address"
                        class="form-control"
                        value="{{ $setting->address }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Facebook</label>
                    <input type="text" name="facebook"
                        class="form-control"
                        value="{{ $setting->facebook }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Youtube</label>
                    <input type="text" name="youtube"
                        class="form-control"
                        value="{{ $setting->youtube }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Zalo</label>
                    <input type="text" name="zalo"
                        class="form-control"
                        value="{{ $setting->zalo }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label>Footer text</label>

                    <textarea name="footer_text"
                        class="form-control"
                        rows="3">{{ $setting->footer_text }}</textarea>

                </div>

            </div>

            <button class="btn btn-primary">
                Lưu cài đặt
            </button>

        </div>
    </div>

</form>

<script>
    function previewLogo(event) {
        const reader = new FileReader();

        reader.onload = function() {
            const output = document.getElementById('logoPreview');
            output.src = reader.result;
        }

        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection