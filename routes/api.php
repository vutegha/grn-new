<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RapportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes pour la gestion des rapports via API
Route::middleware(['auth'])->group(function () {
    Route::get('/rapports', [RapportController::class, 'index'])->name('api.rapports.index');
    Route::post('/rapports', [RapportController::class, 'store'])->name('api.rapports.store');
});
