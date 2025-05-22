<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{--
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/modestcustom.css') }}" rel="stylesheet">


</head>

<body class="login-page bg-body-secondary">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="login-box">
            <div class="login-logo text-center">
                <h3> Super User Login</h3>
            </div>

            <div class="card p-4">
                <form action="{{ route('user-login') }}" method="post" autocomplete="off" id="login-form">
                    @csrf

                    <!-- Email Input -->
                    <div class="mb-3">
                        <label for="email">Enter Email:</label>
                        <div class="input-group">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                                required>
                            <div class="input-group-text">
                                <span class="bi bi-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <!-- User User Type -->
                    <div class="mb-3">
                        <label for="user_type" class="form-label">User Type</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                            <select class="form-control" id="user_type" name="user_type" required>
                                <option value="" selected>Select User Type</option>
                                <option value="Super User">Super User</option>
                            </select>
                        </div>
                    </div>

                    <!-- Password Input (Initially Shown) -->
                    <div id="password-field" class="mb-3">
                        <label for="password">Enter Password:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Enter Password" required>
                            <div class="input-group-text">
                                <span class="bi bi-lock-fill"></span>
                            </div>
                        </div>
                    </div>

                    <!-- OTP Input (Initially Hidden) -->
                    <div id="otp-field" class="mb-3" style="display: none">
                        <label for="otp">Enter OTP:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP">
                            <div class="input-group-text">
                                <span class="bi bi-key"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="row">
                        <div class="col-12 d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="login-btn">Sign In</button>
                            <button type="button" class="btn btn-warning" id="send-otp-btn">Send OTP</button>
                        </div>
                    </div>

                    <div class="text-center mt-2">
                        <a href="{{ route('forgot-password') }}" class="text-primary">Forgot Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>

<script>
    $(document).ready(function () {
        $("#login-btn").hide(); // Hide Sign In button initially

        $("#send-otp-btn").click(function () {
            var email = $("#email").val();
            var userType = $("#user_type").val();
            var password = $("#password").val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (email && userType && password) {
                $.ajax({
                    url: "{{ route('user-login') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        email: email,
                        user_type: userType,
                        password: password
                    },
                    success: function (response) {
                        alert(response.message);

                        // If credentials are correct, show OTP field and Sign In button
                        $("#password-field").hide();
                        $("#otp-field").show();
                        $("#send-otp-btn").hide(); // Hide Send OTP button
                        $("#login-btn").text("Verify OTP").removeClass("btn-primary")
                            .addClass("btn-success").show();
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        alert("Failed to send OTP: " + xhr.responseText);
                    }
                });
            } else {
                alert("Please enter Email, User Type, and Password before requesting OTP.");
            }
        });

        $("#login-form").submit(function (e) {
            e.preventDefault(); // Prevent the default form submission

            var email = $("#email").val();
            var otp = $("#otp").val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            console.log(csrfToken); // Log the CSRF token to check if it's being retrieved correctly

            // AJAX request to verify OTP
            $.ajax({
                url: "{{ route('verify-otp') }}",  // URL for the OTP verification route
                type: "POST",  // POST method
                headers: {
                    'X-CSRF-TOKEN': csrfToken  // CSRF token in header
                },
                data: {
                    email: email,  // Sending email and OTP data
                    otp: otp
                },
                success: function (response) {
                    alert(response.message);  // Display the message from the response
                    if (response.status === 'success') {
                        window.location.href = response.redirect;  // Redirect using response data
                    }
                },
                error: function (xhr) {
                    alert("OTP verification failed: " + xhr.responseText);  // Error message in case of failure
                }
            });
        });
    });

   
</script>