<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\OrderController;
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

Route::middleware('api')->group(function () {
    Route::prefix('clientes')->group(function () {
        Route::post('/', [ClientController::class, 'store']); // Alta de cliente
    });


    Route::prefix('ordenes')->group(function () {
        Route::post('/', [OrderController::class, 'store']); // Alta de orden
        Route::patch('/{orderId}/asignar-reparto', [OrderController::class, 'assignToDistribution']); // Asignar una orden a un reparto
    });


    Route::prefix('repartos')->group(function () {
        Route::post('/', [DistributionController::class, 'store']); // Alta de reparto con vehiculo asignado
        Route::get('/por-fecha', [DistributionController::class, 'getRepartosByDate']); // Listar los repartos de un día
    });

});