<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendMail extends Event
{
    use SerializesModels;
    public $userId,$templatePath;
    public function __construct($userId,$templatePath)
    {
        $this->userId = $userId;
        $this->templatePath = $templatePath;
    }
    public function broadcastOn()
    {
        return [];
    }
}