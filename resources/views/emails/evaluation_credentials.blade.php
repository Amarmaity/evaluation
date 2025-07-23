<!DOCTYPE html>
<html>
<head>
    <title>Evaluation Credentials</title>
</head>
<body>
    <h2>Hello {{ $user->fname }} {{$user->lname}}</h2>

    <p>You have been granted access for {{ config('app.name') }}.</p>

    <p><strong>Your evaluation login credentials are as follows:</strong></p>
    <ul>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>You can log in using the link below:</p>
    <p><a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>

    {{-- <p>If you have any issues, feel free to contact our team.</p> --}}

    <br>
    <p>Regards,<br>
    {{-- {{ config('app.name') }} --}}
    Delostyle Team</p>
</body>
</html>
