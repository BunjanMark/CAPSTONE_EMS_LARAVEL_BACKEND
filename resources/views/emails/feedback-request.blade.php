<!DOCTYPE html>
<html>
<head>
    <title>Feedback Request</title>
</head>
<body>
    <p>Hi {{ $guestName }},</p>
    <p>Thank you for attending {{ $eventName }}!</p>
    <p>We’d love to hear your feedback. Please click the link below to provide your valuable input:</p>
    <a href="{{ $feedbackLink }}">Give Feedback</a>
    <p>Thank you!</p>
</body>
</html>
