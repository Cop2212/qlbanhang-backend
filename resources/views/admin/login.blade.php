<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial; background:#f4f6f9;">

<div style="width:400px; margin:100px auto; padding:30px; background:white; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1);">

    <h2 style="text-align:center;">Admin Login</h2>

    @if(session('error'))
        <p style="color:red; text-align:center;">
            {{ session('error') }}
        </p>
    @endif

    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <div style="margin-bottom:15px;">
            <input type="email" name="email" placeholder="Email"
                   style="width:100%; padding:10px;" required>
        </div>

        <div style="margin-bottom:15px;">
            <input type="password" name="password" placeholder="Password"
                   style="width:100%; padding:10px;" required>
        </div>

        <button type="submit"
                style="width:100%; padding:10px; background:#007bff; color:white; border:none;">
            Login
        </button>
    </form>

</div>

</body>
</html>