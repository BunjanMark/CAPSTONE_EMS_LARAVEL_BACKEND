<!DOCTYPE html>
<html>
<head>
    <title>Password Recovery</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            text-align: center;
        }
        .title {
            color: #DAA520;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .message {
            margin-bottom: 20px;
            color: #333;
            line-height: 1.5;
        }
        .button {
            background-color: #DAA520;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            display: inline-block;
            margin-top: 10px;
        }
        .button:hover {
            background-color: #c99e1a;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">Password Recovery</h1>
        <p class="message">Hi,</p>
        <p class="message">Click the button below to reset your password:</p>
        <a href="{{ $recoveryUrl }}" class="button">Reset Password</a>
        <p class="footer">If you did not request this, please ignore this email.</p>
    </div>
</body>
</html>
