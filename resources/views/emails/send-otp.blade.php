<!DOCTYPE html>
<html>

<head>
    <title>Quên mật khẩu</title>
</head>

<body>
    <p>Xin chào {{ $user->name }},</p>
    <p>Mã OTP để đặt lại mật khẩu của bạn là: <strong>{{ $otp }}</strong></p>
    <p>OTP có hiệu lực trong 10 phút.</p>
    <p>Nếu bạn không thực hiện thao tác, vui lòng bỏ qua tin nhắn này!</p>
    <p>Trân trọng,<br>{{ config('app.name') }}</p>
</body>

</html>
