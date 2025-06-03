<!DOCTYPE html>
<html>

<head>
    <title>Email liên hệ từ người dùng</title>
</head>

<body>
    <h2>Tin nhắn mới từ khách hàng</h2>
    <p><strong>Họ tên:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Nội dung:</strong></p>
    <p>{{ $data['message'] }}</p>
    <p>Trân trọng,<br>{{ config('app.name') }}</p>
</body>

</html>
