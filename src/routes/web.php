<?php

use Illuminate\Support\Facades\Route;
use kpr\VideoCall\Http\Controllers\VideoCallController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/video-call/{room}', [VideoCallController::class, 'room'])->name('video-call.room');
    Route::post('/video-call/signal', [VideoCallController::class, 'signal'])->name('video-call.signal');
}); 