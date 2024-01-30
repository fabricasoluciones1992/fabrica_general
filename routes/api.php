<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CivilStatesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DiseasesController;
use App\Http\Controllers\DocTypesController;
use App\Http\Controllers\EpsController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\MailsController;
use App\Http\Controllers\MulticulturalismsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewTypeController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RelationshipsController;
use App\Http\Controllers\TelephonesController;
use App\Models\multiculturalisms;
use App\Models\relationships;
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
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth:sanctum'])->group(function() {
    Route::resource('access', AccessController::class)->names('access');
    Route::resource('areas', AreaController::class)->names('areas');
    Route::resource('contacts', ContactController::class)->names('contacts');
    Route::resource('doctypes', DocTypesController::class)->names('doctypes');
    Route::resource('eps', EpsController::class)->names('eps');
    Route::resource('genders', GenderController::class)->names('genders');
    Route::resource('localities', LocalityController::class)->names('localities');
    Route::resource('news', NewsController::class)->names('news');
    Route::resource('new_types', NewTypeController::class)->names('new_types');
    Route::resource('persons', PersonController::class)->names('persons');
    Route::resource('projects', ProjectController::class)->names('projects');
    Route::resource('positions', PositionController::class)->names('positions');
    Route::resource('multiculturalisms', MulticulturalismsController::class)->names('multiculturalisms');
    Route::resource('civilstates', CivilStatesController::class)->names('civilstates');
    Route::resource('relationships', RelationshipsController::class)->names('relationships');
    Route::resource('disaeses', DiseasesController::class)->names('disaeses');
    Route::resource('mails', MailsController::class)->names('mails');
    Route::resource('telephones', TelephonesController::class)->names('telephones');
    Route::resource('medicalHistories', medicalHistories::class)->names('medicalHistories');
 
});



