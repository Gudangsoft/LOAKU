<?php

use App\Http\Controllers\Api\LoaVerifyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — LOA SIPTENAN
|--------------------------------------------------------------------------
| Base URL: /api/v1
| Semua endpoint bersifat publik (tidak perlu autentikasi).
| Format respons: JSON
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // Verifikasi LOA berdasarkan kode
    // GET /api/v1/loa/{code}
    Route::get('/loa/{code}', [LoaVerifyController::class, 'verify'])
        ->name('api.loa.verify')
        ->where('code', '[A-Za-z0-9]+');

});
