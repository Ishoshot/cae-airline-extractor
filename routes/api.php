<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\RosterUploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::prefix('rosters')->group(function () {
    Route::post('/', RosterUploadController::class)->name('roster.store');
});


Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('event.list');
    Route::get('/standby', [EventController::class, 'listStandbyEvents'])->name('event.standby.list');
});


Route::prefix('flights')->group(function () {
    Route::get('/', [FlightController::class, 'index'])->name('flight.list');
    Route::get('/from/{location}', [FlightController::class, 'listFlightsFromLocation'])->name('flight.location.list');
});
