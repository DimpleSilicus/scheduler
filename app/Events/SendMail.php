<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendMail extends Event
{
    use SerializesModels;
    public $userId;
    public function __construct($userId)
    {
        $this->userId = $userId;
    }
    public function broadcastOn()
    {
        return [];
    }
}