<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Probation Completed</title>
</head>
<body>
    <p>Dear {{ $user->fname }} {{ $user->lname }},</p>

    <p>Congratulations! Your probation period has been successfully completed as of {{ \Carbon\Carbon::parse($user->probation_date)->format('F j, Y') }}.</p>

    {{-- <p>Your employment status has now been updated to <strong>Confirmed</strong>.</p> --}}

    <p>We are excited to continue working with you!</p>

    <p>Best regards,<br>HR Team</p>
</body>
</html>
