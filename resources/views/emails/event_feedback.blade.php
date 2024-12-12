
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            background-color: #ffffff;
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header img {
            width: 150px;
            border-radius: 50%;
        }
        .event-details {
            margin: 20px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 6px;
        }
        .event-details h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .event-details p {
            font-size: 16px;
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #777;
        }
        .footer a {
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ $event->eventCoverPhoto }}" alt="Event Image">
            <h1>{{ $event->name }} Starts in 1 Day</h1>
        </div>

        <div class="event-details">
            <h2>EVENT FEEDBACK!!</h2>
            <p><strong>Type:</strong> {{ $event->type }}</p>
            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->date)->format('F d, Y') }}</p>

            <p><strong>Time:</strong> 
                {{ \Carbon\Carbon::parse($event->time)->format('g:i A') }}
            </p>
            <p><strong>Location:</strong> {{ $event->location }}</p>
            <p><strong>Description:</strong> {{ $event->description }}</p>
            <p><strong>Status:</strong> {{ ucfirst($event->status) }}</p>
            <p><strong>Expected Attendees:</strong> {{ $event->pax }} people</p>
        </div>

        <div class="footer">
            <p>We hope to see you there! If you have any questions, feel free to <a href="mailto:{{ $event->organizer_email }}">contact us</a>.</p>
            <p>&copy; {{ date('Y') }} Event Management, All rights reserved.</p>
        </div>
    </div>
</body>
</html>