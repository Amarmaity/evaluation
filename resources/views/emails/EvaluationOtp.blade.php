<!DOCTYPE html>
<html>
<head>
    <title>Evaluation OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h2 {
            color: #333;
        }
        .otp-code {
            font-size: 22px;
            font-weight: bold;
            color: #70e63a;
            padding: 10px;
            border: 2px dashed #6af034;
            display: inline-block;
            margin: 15px 0;
        }
        .footer {
            font-size: 12px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your OTP Code</h2>
        <p>Hello,</p>
        <p>Your OTP code is:</p>
        <div class="otp-code">{{ $otp }}</div>
        <p>This code is valid for a short period. Do not share it with anyone.</p>
        <p>Thank you!</p>
        <p class="footer">If you did not request this, please ignore this email.</p>
    </div>
</body>
</html>
