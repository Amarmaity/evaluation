<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        body {
            background-color: #f8f9fa; /* Light Gray Background */
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

        .login-logo h3 {
            font-weight: bold;
            color: #333;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: #555;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            text-align: center;
            font-size: 18px;
            letter-spacing: 4px;
            font-weight: bold;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
        }

        .text-primary {
            display: block;
            text-align: center;
            margin-top: 10px;
            font-weight: 500;
        }

        .text-primary:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center align-items-center">
        <div class="login-box">
            <div class="login-logo text-center">
                <h3>Verify OTP</h3>
            </div>

            <div class="card p-4">
                <form action="{{ route('forgot-password.verify.other') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="otp">Enter OTP:</label>
                        <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP" required>
                    </div>
                
                    <div class="row">
                        <div class="col-12 d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Verify OTP</button>
                        </div>
                    </div>                
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
