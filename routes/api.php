<?php

use App\Http\Controllers\TimetableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('/searchTimetable/{name}/{type}', [TimetableController::class, 'searchTimetable']);
Route::post('/getTimetable/{type}/{numGroup}/{weekNum}/{date1}/{date2}', [TimetableController::class, 'getTimetable']);
Route::get('/index', [TimetableController::class, 'index']);

