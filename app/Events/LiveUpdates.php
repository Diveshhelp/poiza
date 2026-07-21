<?php

namespace App\Events;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
 

class LiveUpdates  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $teamId;
    /**
     * Create a new event instance.
     */
    public function __construct($teamId)
    {
        $this->teamId = $teamId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

    public function broadcastOn()
    {
        return new Channel('team.'.$this->teamId);

    }
}
