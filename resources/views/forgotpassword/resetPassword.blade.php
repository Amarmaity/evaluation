<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-box {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center">
    <div class="login-box">
        <div class="login-logo text-center">
            <h3>Reset Password</h3>
        </div>

        <!-- Show success or error message -->
        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('forgot-password.reset') }}" method="POST">
            @csrf

            <!-- Password Input -->
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control" name="password" required>
                @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            </div>

            <!-- Confirm Password Input -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success w-100">Reset Password</button>

            <!-- Back to Login Link -->
            <div class="mt-3 text-center">
                @if(Session::has('forgot_password_user_type'))
                    @if(Session::get('forgot_password_user_type') === 'super_admin')
                        <a href="{{ route('all-user-login') }}" class="text-primary">Back to Super Admin Login</a>
                    @else
                        <a href="{{ route('all-user-login') }}" class="text-primary">Back to User Login</a>
                    @endif
                @endif
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>