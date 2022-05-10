<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/',                      [HomeController::class, 'index']);

Route::post('/timetable/api/searchTimetable/{name}/{type}', [\App\Http\Controllers\TimetableController::class, 'searchTimetable']);
Route::post('/timetable/api/getTimetable/{query}/{type}/{numGroup}', [\App\Http\Controllers\TimetableController::class, 'getTimetable']);


Route::get('/phpInfo', function() {
    return response()->json([
        'stuff' => phpinfo()
    ]);
});

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
