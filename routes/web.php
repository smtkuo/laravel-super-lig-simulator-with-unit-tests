<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TournamentTeamsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [TournamentTeamsController::class, 'index'])->name('homepage');

Route::post('/generate-fixtures', [TournamentTeamsController::class, 'generateFixtures']);
Route::get('/generate-fixtures', [TournamentTeamsController::class, 'generatedFixtures'])->name('generatedFixtures');
Route::get('/simulation', [TournamentTeamsController::class, 'simulate'])->name('simulation');
Route::get('/play-all-weeks', [TournamentTeamsController::class, 'playAllWeeks'])->name('play-all-weeks');
Route::get('/play-next-week', [TournamentTeamsController::class, 'playNextWeek'])->name('play-next-week');
Route::get('/reset-tournament', [TournamentTeamsController::class, 'resetTournament'])->name('reset-tournament');
