<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/modestcustom.css') }}?v=14" rel="stylesheet">
</head>

<body class="login-page bg-body-secondary">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="login-box">
            <div class="login-logo text-center">
                @if($superUser== null) 
                <h3>User Login</h3>
                @elseif($superUser)
                <h3>Super User Login</h3>
                @endif
            </div>
            <div class="card p-4">
                <form method="post" autocomplete="off" id="login-form">
                    @csrf

                    <!-- Email Input -->
                    <div class="mb-3">
                        <label for="email">Enter Email:</label>
                        <div class="input-group">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                            <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                        </div>
                    </div>
                   
                    <!-- User Type Dropdown -->
                   
                    <div class="mb-3">
                        <label for="user_type_dropdown" class="form-label">User Type</label>
                        <select class="form-control" id="user_type_dropdown" name="user_type" required>
                            <option value="" selected disabled>Select User Type</option>
                           
                            <option value="Super User">Super User</option>
                            @if($superUser == null)
                        
                            <option value="admin">Admin</option>
                            <option value="hr">HR</option>
                            <option value="users">Users</option>
                            <option value="manager">Manager</option>
                            <option value="client">Client</option>
                            @endif
                        </select>
                    </div>

                    <!-- Password Input (Initially Shown) -->
                    <div id="password-field" class="mb-3">
                        <label for="password">Enter Password:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" required>
                            <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                        </div>
                    </div>

                    <!-- OTP Input (Initially Hidden) -->
                    <div id="otp-field" class="mb-3" style="display: none">
                        <label for="otp">Enter OTP:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP">
                            <div class="input-group-text"><span class="bi bi-key"></span></div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-warning" id="send-otp-btn">Send OTP</button>
                        <button type="submit" class="btn btn-primary" id="login-btn" style="display: none;">Verify OTP</button>
                    </div>

                    {{-- <div class="text-center mt-2">
                        <a href="{{ route('forgot-password') }}" class="text-primary">Forgot Password?</a>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $("#send-otp-btn").click(function () {
                var email = $("#email").val();
                var userType = $("#user_type_dropdown").val();
                var password = $("#password").val();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                // Ensure all required fields are filled
                if (email && userType && (password || $("#password-field").is(":hidden"))) {
                    $.ajax({
                        url: "{{ route('log-in') }}",
                        type: "POST",
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        data: { email: email, user_type: userType, password: password },
                        success: function (response) {
                            alert(response.message);
                            if (response.status === 'success') {
                                $("#password-field").hide();
                                $("#otp-field").show();
                                $("#send-otp-btn").hide();
                                $("#login-btn").show().text("Verify OTP").removeClass("btn-primary").addClass("btn-success");
                            }
                        },
                        error: function (xhr) {
                            alert("Failed to send OTP: " + xhr.responseJSON.message);
                        }
                    });
                } else {
                    alert("Please enter email, user type, and password before requesting OTP.");
                }
            });

            $("#login-form").submit(function (e) {
                e.preventDefault();
                var email = $("#email").val();
                var otp = $("#otp").val();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                if (otp) {
                    $.ajax({
                        url: "{{ route('verify-otp-login-users') }}",
                        type: "POST",
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        data: { email: email, otp: otp },
                        success: function (response) {
                            alert(response.message);
                            if (response.status === 'success') {
                                window.location.href = response.redirect;
                            }
                        },
                        error: function (xhr) {
                            alert("OTP verification failed: " + xhr.responseJSON.message);
                        }
                    });
                } else {
                    alert("Please enter the OTP.");
                }
            });
        });      
    </script>

</body>
</html>
