<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Twilio\Rest\Client;

class TwilioRestClientProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            Client::class, function ($app) {
                $accountSid = config('services.twilio')['accountSid'];
                $authToken = config('services.twilio')['authToken'];
//                $userName = config('services.twilio')['userName'];
//                $password = config('services.twilio')['password'];
                return new Client($accountSid, $authToken);
            }
        );
    }
}
