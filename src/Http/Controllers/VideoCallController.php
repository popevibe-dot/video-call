<?php

namespace pkc\VideoCall\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class VideoCallController extends Controller
{
    public function room($room)
    {
        return view('video-call::room', [
            'room' => $room,
            'user' => Auth::user(),
            'stunServers' => config('video-call.stun_servers'),
            'turnServers' => config('video-call.turn_servers'),
            'videoConstraints' => config('video-call.video_constraints'),
        ]);
    }

    public function signal(Request $request)
    {
        $user = Auth::user();
        $targetUserId = $request->input('target_user_id');
        $signal = $request->input('signal');
        
        // Broadcast the signal to the target user using Laravel Reverb
        broadcast(new \pkc\VideoCall\Events\VideoCallSignal(
            $targetUserId,
            $user->id,
            $signal
        ));

        return response()->json(['success' => true]);
    }
} 