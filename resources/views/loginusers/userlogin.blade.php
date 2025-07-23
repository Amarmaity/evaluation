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

    <style>
        /* ----------------------------- Login Page -------------------------------- */

        .login__page--container {
            max-width: 570px;
            margin: 70px auto;
        }

        .login__page--container h1 {
            font-size: 28px;
            line-height: 1.2;
            font-weight: 500;
            color: #333333;
            margin: 0 0 30px;
            text-align: center;
        }

        .login__page--form label {
            font-size: 16px;
            color: #666666;
            line-height: 1;
            margin: 0 0 10px;
        }

        .login__page--form .form-group {
            margin-bottom: 20px;
        }

        .login__page--form .form-control {
            height: 48px;
            line-height: 48px;
        }

        .forget__pass {
            float: right;
            font-size: var(--text-base);
            line-height: 1;
            color: #111111;
            text-decoration: underline;
            padding-top: 10px;
        }

        .forget__pass:hover {
            text-decoration-color: transparent;
        }


        .label__checkbox {
            display: block;
            position: relative;
            padding-left: 35px;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .login__page--form label.label__checkbox {
            margin: 50px 0 40px;
            color: #333333;
        }

        /* Hide the browser's default checkbox */
        .label__checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: -2px;
            left: 0;
            height: 20px;
            width: 20px;
            background-color: transparent;
            border: 2px solid #111111;
            border-radius: 4px;
            transition: all 0.3s ease;
        }


        .label__checkbox:hover input~.checkmark {
            background-color: rgba(17, 17, 17, .5);
            border-color: transparent;
        }

        .label__checkbox input:checked~.checkmark {
            background-color: #111111;
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .label__checkbox input:checked~.checkmark:after {
            display: block;
        }

        .label__checkbox .checkmark:after {
            left: 5px;
            top: -1px;
            width: 8px;
            height: 14px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        .btn__theme {
            display: block;
            border-radius: 8px;
            color: #ffffff;
            width: 100%;
            margin-top: 30px !important;
            padding: 12px;
            border: none;
            font-size: var(--text-lg);
            font-weight: 500;
            -webkit-transition: 0.3s linear;
            -moz-transition: 0.3s linear;
            -ms-transition: 0.3s linear;
            -o-transition: 0.3s linear;
            transition: 0.3s linear;
            text-align: center;
            text-underline-position: from-font;
            text-decoration-skip-ink: none;
            position: relative;
            z-index: 2;
            overflow: hidden;
        }

        .btn__theme:before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #00274d;
            transition: all .3s;
            border-radius: 8px;
            z-index: -2;
        }

        .btn__theme:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background-color: #FF6600;
            transition: all .3s;
            border-radius: 8px;
            z-index: -1;
        }

        .btn__theme:hover::after,
        .btn__theme:focus::after {
            width: 100%;
        }

        .login__page--form select.form-control {
            line-height: inherit;
            appearance: auto;
        }

        .resend__otp {
            margin-top: 6px !important;
        }
    </style>
</head>

<body class="login-page">
    <section class="login__page--wrapper">
        <div class="container">
            <div class="login__page--container">
                <div class="login__page--logo text-center mb-5">
                    <img src="{{ asset('images/evalon-black.png') }}" alt="Delostyle Logo" class="mw-100">
                </div>
                <h1>Sign in to your Evalon Panel</h1>
                <form method="post" autocomplete="off" id="login-form" class="login__page--form">
                    @csrf

                    <!-- Email Input -->
                    <div class="form-group">
                        <label for="email">Enter Email:</label>
                        <div class="input-group">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                                required>
                            <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                        </div>
                    </div>

                    <!-- User Type Dropdown -->

                    <div class="form-group">
                        <label for="user_type_dropdown" class="form-label">User Type:</label>
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
                    <div id="password-field" class="form-group">
                        <label for="password">Enter Password:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Enter Password" required>
                            <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                        </div>
                    </div>

                    <!-- OTP Input (Initially Hidden) -->
                    <div id="otp-field" class="form-group" style="display: none">
                        <label for="otp">Enter OTP:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP">
                            <div class="input-group-text"><span class="bi bi-key"></span></div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn__theme" id="send-otp-btn">Send OTP</button>
                        <button type="submit" class="btn__theme" id="login-btn" style="display: none;">Verify
                            OTP</button>
                        <button type="button" class="btn__theme resend__otp" id="resend-otp-btn" style="display: none;">Resend
                            OTP</button>
                    </div>

                    <div class="text-center mt-2">
                        <a href="{{ route('forgot-password') }}" class="forget__pass">Forgot Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        function showBtnLoader(btn) {
            const $btn = $(btn);
            $btn.addClass('btn-loading');

            if ($btn.is("#send-otp-btn")) {
                $btn.text("Sending OTP...");
            } else if ($btn.is("#login-btn")) {
                $btn.text("Verifying...");
            }
        }

        function hideBtnLoader(btn) {
            const $btn = $(btn);
            $btn.removeClass('btn-loading');

            if ($btn.is("#send-otp-btn")) {
                $btn.text("Send OTP");
            } else if ($btn.is("#login-btn")) {
                $btn.text("Verify OTP");
            }
        }

        $(document).ready(function() {
            const otpCooldownSeconds = 30;
            let resendTimeout;
            let isOtpSending = false;

            function startCooldown() {
                let secondsLeft = otpCooldownSeconds;
                const $resendBtn = $("#resend-otp-btn");

                $resendBtn.text(`Resend OTP in ${secondsLeft}s`).show().prop("disabled", true);

                resendTimeout = setInterval(() => {
                    secondsLeft--;
                    if (secondsLeft > 0) {
                        $resendBtn.text(`Resend OTP in ${secondsLeft}s`);
                    } else {
                        clearInterval(resendTimeout);
                        $resendBtn.text("Resend OTP").prop("disabled", false);
                        isOtpSending = false;
                    }
                }, 1000);
            }

            function sendOtp(isResend = false) {
                if (isOtpSending) return;

                const email = $("#email").val();
                const userType = $("#user_type_dropdown").val();
                const password = $("#password").val();
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                isOtpSending = true;
                $("#send-otp-btn, #resend-otp-btn").prop("disabled", true);

                $.ajax({
                    url: "{{ route('log-in') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        email,
                        user_type: userType,
                        password
                    },
                    success: function(response) {
                        hideBtnLoader("#send-otp-btn");
                        alert(response.message);

                        if (response.status === 'success') {
                            $("#password-field").hide();
                            $("#otp-field").show();
                            $("#send-otp-btn").hide();
                            $("#login-btn").show().text("Verify OTP").removeClass("btn-primary").addClass("btn-success");
                            $("#resend-otp-btn").hide();
                            startCooldown();
                        } else {
                            isOtpSending = false;
                            $("#send-otp-btn, #resend-otp-btn").prop("disabled", false);
                        }
                    },
                    error: function(xhr) {
                        hideBtnLoader("#send-otp-btn");
                        alert("Failed to send OTP: " + (xhr.responseJSON?.message || "Server Error"));
                        isOtpSending = false;
                        $("#send-otp-btn, #resend-otp-btn").prop("disabled", false);
                    }
                });
            }

            $("#send-otp-btn").click(function() {
                const email = $("#email").val();
                const userType = $("#user_type_dropdown").val();
                const password = $("#password").val();

                if (!email || !userType || (!password && $("#password-field").is(":visible"))) {
                    alert("Please enter email, user type, and password before requesting OTP.");
                    return;
                }

                showBtnLoader(this);
                sendOtp(false);
            });

            $("#resend-otp-btn").click(function() {
                showBtnLoader(this);
                sendOtp(true);
            });

            $("#login-form").submit(function(e) {
                e.preventDefault();
                const email = $("#email").val();
                const otp = $("#otp").val();
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                if (otp) {
                    showBtnLoader("#login-btn");

                    $.ajax({
                        url: "{{ route('verify-otp-login-users') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: {
                            email,
                            otp
                        },
                        success: function(response) {
                            hideBtnLoader("#login-btn");
                            alert(response.message);
                            if (response.status === 'success') {
                                clearTimeout(resendTimeout);
                                window.location.href = response.redirect;
                            }
                        },
                        error: function(xhr) {
                            hideBtnLoader("#login-btn");
                            alert("OTP verification failed: " + (xhr.responseJSON?.message || "Server Error"));
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