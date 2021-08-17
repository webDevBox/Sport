<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Twilio\Rest\Client;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $accountSid = config('services.twilio')['TWILIO_ACCOUNT_SID'];
        $authToken  = config('services.twilio')['TWILIO_AUTH_TOKEN'];
        $fromNumber = config('services.twilio')['TWILIO_FROM_NUMBER'];
        $otpMsgBody = $this->details['message'];

        $client = new Client($accountSid, $authToken);

        try
        {
            $client->messages->create(
                // the number you'd like to send the message to
                $this->details['phone'],
            array(
                    'from' => $fromNumber,
                    'body' => $otpMsgBody.$this->details['otp'].'.',
            )
            );
        }
        catch (Exception $e)
        {
            return response()->error('OTP_NOT_SEND');
        }
    }
}
