<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControllerCards;
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
Route::post('/inicio/basico', [ControllerCards::class, 'LoginBasic']);
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/lista/tarjeta', [ControllerCards::class, 'Index']);
Route::post('/registrar/tarjeta', [ControllerCards::class, 'Insert']);
Route::post('/modificar/tarjeta', [ControllerCards::class, 'Update']);
Route::post('/eliminar/tarjeta', [ControllerCards::class, 'Delete']);
Route::post('/detalle/tarjeta', [ControllerCards::class, 'Detal']);

// lista
Route::get('/lista/rareza', [ControllerCards::class, 'List_Rarity']);
Route::get('/lista/tipo', [ControllerCards::class, 'List_Type']);
Route::get('/lista/expansion', [ControllerCards::class, 'List_Expansion']);