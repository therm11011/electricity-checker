<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class UpdateDeviceStatus implements ShouldBroadcast
{
    use SerializesModels;

    public function __construct(public $device) {}

    public function broadcastOn(): array
    {
        // This is the "radio station" name
        return [new \Illuminate\Broadcasting\Channel('devices')];
    }
}

