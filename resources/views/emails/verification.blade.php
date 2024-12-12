<!-- resources/views/emails/verification.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Verify Your Email Address</title>
</head>
<body>
    <h2>Hello,</h2>
    <p>Your verification code is: <strong>{{ $code }}</strong></p>
    <p>If you did not request this, please ignore this email.</p>
</body>
</html>
