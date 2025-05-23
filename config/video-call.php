<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Video Call Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the video call package.
    |
    */

    // STUN servers for WebRTC connection
    'stun_servers' => [
        'stun:stun.l.google.com:19302',
        'stun:stun1.l.google.com:19302',
    ],

    // TURN servers (you should add your own TURN servers in production)
    'turn_servers' => [
        // [
        //     'urls' => 'turn:your-turn-server.com:3478',
        //     'username' => 'username',
        //     'credential' => 'credential'
        // ]
    ],

    // Maximum call duration in minutes (0 for unlimited)
    'max_call_duration' => 0,

    // Video constraints
    'video_constraints' => [
        'width' => 640,
        'height' => 480,
        'frameRate' => 30,
    ],
]; 