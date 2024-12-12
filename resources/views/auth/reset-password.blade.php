<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .reset-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .reset-container h1 {
            color: #DAA520;
            margin-bottom: 20px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .form-group label {
            color: #DAA520;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #DAA520;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group input:focus {
            outline-color: #DAA520;
        }

        button {
            background-color: #DAA520;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    <div class="reset-container">
        <h1>Reset Your Password</h1>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <button type="submit">Reset Password</button>
        </form>
    </div>

</body>
</html>
