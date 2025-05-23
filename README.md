# Laravel Video Call Package

A robust Laravel package for implementing video calling functionality using WebRTC and Laravel Reverb for signaling. This package provides a simple way to add peer-to-peer video calls to your Laravel application without relying on external services like Pusher.

## Features

- 🎥 Real-time video and audio calls using WebRTC
- 🔄 Laravel Reverb for WebSocket signaling
- 🔒 Private channels for secure communication
- 🎛️ Mute audio/video controls
- 📱 Responsive design
- 🚀 No external service dependencies
- 🛠️ Easy to customize and extend

## Requirements

- PHP 8.1 or higher
- Laravel 11.31
- Laravel Reverb for WebSocket support
- Modern browser with WebRTC support

## Installation

1. Install the package via Composer:

```bash
composer require kpr/video-call
```

2. Publish the package assets:

```bash
php artisan vendor:publish --provider="kpr\VideoCall\VideoCallServiceProvider"
```

3. Configure Laravel Reverb in your `.env` file:

```env
BROADCAST_DRIVER=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http
```

## Basic Usage

1. Include the necessary JavaScript in your layout:

```html
<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Add Laravel Echo and Reverb client -->
<script src="{{ asset('vendor/reverb/reverb.js') }}"></script>
<script>
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: '{{ config('broadcasting.connections.reverb.key') }}',
        wsHost: window.location.hostname,
        wsPort: 8080,
        forceTLS: false,
        enabledTransports: ['ws', 'wss'],
    });
</script>
```

2. Create a video call room:

```php
Route::get('/video-call/{room}', function($room) {
    return view('video-call::room', [
        'room' => $room,
        'targetUserId' => request()->query('target_user'),
    ]);
})->middleware(['auth']);
```

3. Start a video call:

```php
// In your blade view
<a href="{{ route('video-call.room', ['room' => 'unique-room-id', 'target_user' => $userId]) }}">
    Start Video Call
</a>
```

## Configuration

You can modify the package configuration in `config/video-call.php`:

```php
return [
    'stun_servers' => [
        'stun:stun.l.google.com:19302',
        'stun:stun1.l.google.com:19302',
    ],
    'turn_servers' => [
        // Add your TURN servers here
        // [
        //     'urls' => 'turn:your-turn-server.com:3478',
        //     'username' => 'username',
        //     'credential' => 'credential'
        // ]
    ],
    'video_constraints' => [
        'width' => 640,
        'height' => 480,
        'frameRate' => 30,
    ],
];
```

## Events

The package broadcasts the following events:

- `VideoCallSignal`: Handles WebRTC signaling between peers

## Security

- Uses private channels for signaling
- All routes are protected by authentication middleware
- WebRTC connections are encrypted by default
- TURN servers should be configured in production for NAT traversal

## Production Considerations

1. Use HTTPS in production
2. Configure TURN servers for reliable connectivity
3. Implement proper user authorization
4. Consider scaling WebSocket connections
5. Monitor server resources

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the MIT license.

## Credits

- Author: [Roktim Ariyan](mailto:laradev.sumon@gmail.com)
- Built with Laravel and WebRTC
- Special thanks to the Laravel community

## Support

For support, please create an issue in the GitHub repository or contact the author directly. 