<?php

namespace pkc\VideoCall;

use Illuminate\Support\ServiceProvider;

class VideoCallServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/video-call.php', 'video-call'
        );
    }

    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'video-call');

        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/video-call.php' => config_path('video-call.php'),
        ], 'config');

        // Publish views
        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/video-call'),
        ], 'views');

        // Publish assets
        $this->publishes([
            __DIR__.'/resources/js' => public_path('vendor/video-call/js'),
            __DIR__.'/resources/css' => public_path('vendor/video-call/css'),
        ], 'assets');
    }
} 