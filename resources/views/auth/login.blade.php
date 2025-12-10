<!DOCTYPE html>
<html>
<head>
    <title>AbleLink Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light d-flex align-items-center justify-content-center" style="min-height:100vh;">

<div class="card p-4 shadow" style="width: 350px;">
    <h4 class="text-center mb-3">AbleLink Demo Login</h4>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="text-muted text-center mt-3" style="font-size:12px;">
        * Demo login only. No OTP or password required.
    </p>
</div>

</body>
</html>
