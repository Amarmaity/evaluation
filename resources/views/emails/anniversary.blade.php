<!DOCTYPE html>
<html>
<head>
    <title>Anniversary Congrats</title>
</head>
<body>
    <h2>Hi {{ $user->fname }} {{ $user->lname }},</h2>

    <p>🎉 Congratulations on completing one year with us!</p>
    <p>We appreciate your contributions and dedication.</p>

    <p>Wishing you continued success at {{ config('app.name') }}.</p>

    <br>
    <p>Best regards,</p>
    {{-- <p>HR Team</p> --}}
</body>
</html>
