<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Call - {{ $room }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('vendor/video-call/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="video-container">
        <div id="local-video-wrapper" class="video-wrapper">
            <video id="local-video" autoplay playsinline muted></video>
            <div class="video-overlay">
                <span class="name-tag">You</span>
            </div>
        </div>
        <div id="remote-video-wrapper" class="video-wrapper">
            <video id="remote-video" autoplay playsinline></video>
            <div class="video-overlay">
                <span class="name-tag">Remote User</span>
            </div>
        </div>
    </div>

    <div class="controls">
        <button id="toggle-video" class="control-btn">
            <i class="fas fa-video"></i>
        </button>
        <button id="toggle-audio" class="control-btn">
            <i class="fas fa-microphone"></i>
        </button>
        <button id="end-call" class="control-btn danger">
            <i class="fas fa-phone-slash"></i>
        </button>
    </div>

    <script>
        window.videoCallConfig = {
            user: @json(auth()->user()),
            room: @json($room),
            targetUserId: @json($targetUserId),
            stunServers: @json(config('video-call.stun_servers', [])),
            turnServers: @json(config('video-call.turn_servers', [])),
            videoConstraints: @json(config('video-call.video_constraints', [
                'width' => 640,
                'height' => 480,
                'frameRate' => 30,
            ])),
        };
    </script>
    <script src="{{ asset('vendor/reverb/reverb.js') }}"></script>
    <script>
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ config('broadcasting.connections.reverb.key') }}',
            wsHost: window.location.hostname,
            wsPort: {{ config('broadcasting.connections.reverb.port', 8080) }},
            forceTLS: false,
            enabledTransports: ['ws', 'wss'],
        });
    </script>
    <script src="{{ asset('vendor/video-call/js/webrtc.js') }}"></script>
</body>
</html> 