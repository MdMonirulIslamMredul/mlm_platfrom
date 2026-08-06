<?php

use App\Http\Controllers\ImmiAssetProxyController;
use Illuminate\Support\Facades\Route;

// Add these two lines to your existing routes/web.php

Route::view('/immigration-and-citizenship', 'immigration-citizenship');

Route::get('/vendor-proxy/{path}', [ImmiAssetProxyController::class, 'show'])
    ->where('path', '.*')
    ->name('immi.asset-proxy');
