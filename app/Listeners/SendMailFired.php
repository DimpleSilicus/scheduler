<?php

namespace App\Listeners;
use App\Events\SendMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Mail;

class SendMailFired
{
    public function __construct()
    {
        
    }
    public function handle(SendMail $event)
    {        
        $user = User::find($event->userId)->toArray();
        
        $messageTemplate['email'] = $user['email'];
        
        Mail::send('emails.'.$event->templatePath, $messageTemplate, function($message) use ($messageTemplate) {
            $message->to($messageTemplate['email']);
            $message->subject('Event');
        });
    }
}