<?php

namespace App\Services\Communications;

use Twilio\Rest\Client;

class TwilioService
{
    protected $twilio;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        
        // Initialize the Twilio client
        $this->twilio = new Client($sid, $authToken);
    }

    // Function to send SMS
    public function sendSMS(string $to, string $message)
    {
        // Retrieve Twilio phone number from .env file
        $from = env('TWILIO_PHONE_NUMBER');
        
        // Send the SMS
        return $this->twilio->messages->create(
            $to, 
            [
                'from' => $from,
                'body' => $message,
            ]
        );
    }

    // Optional: Function to check message status
    public function checkMessageStatus(string $messageSid)
    {
        return $this->twilio->messages($messageSid)->fetch();
    }
}
