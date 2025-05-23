<?php

namespace pkc\VideoCall\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoCallSignal implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $targetUserId,
        public int $fromUserId,
        public array $signal
    ) {}

    public function broadcastOn()
    {
        return new PrivateChannel('video-call.' . $this->targetUserId);
    }

    public function broadcastAs()
    {
        return 'video-call.signal';
    }
} 