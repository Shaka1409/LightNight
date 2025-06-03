<!DOCTYPE html>
<html>
<head>
    <title>Đăng ký thành công</title>
</head>
<body>
    <h1>Chúc mừng bạn đã đăng ký thành công!</h1>
    <p>Xin chào {{ $user->name }},</p>
    <p>Cảm ơn bạn đã đăng ký tại {{ config('app.name') }}.</p>
    <p>Email của bạn: {{ $user->email }}</p>
    <p>Trân trọng,<br>{{ config('app.name') }}</p>
</body>
</html>