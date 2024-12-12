<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Http\Request;
use App\Services\Communications\TwilioService;
 

class TwilioSmsController extends Controller
{
    //
      /**
     * Test sms send
     * @return mixed
     */
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function sendTest()
    {
        $to = '+639161508298';  // The recipient's phone number
        $message = 'Hello, this is a test message from Twilio!';

        // Call the sendSMS method
        $response = $this->twilioService->sendSMS($to, $message);

        return response()->json([
            'status' => 'Message Sent',
            'sid' => $response->sid
        ]);
    }
}




 