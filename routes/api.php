<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocTypesController;
use App\Http\Controllers\EpsController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewTypeController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProjectController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('access', AccessController::class)->names('access');
Route::apiResource('areas', AreaController::class)->names('areas');
Route::apiResource('contacts', ContactController::class)->names('contacts');
Route::apiResource('doctypes', DocTypesController::class)->names('doctypes');
Route::apiResource('eps', EpsController::class)->names('eps');
Route::apiResource('genders', GenderController::class)->names('genders');
Route::apiResource('localities', LocalityController::class)->names('localities');
Route::apiResource('news', NewsController::class)->names('news');
Route::apiResource('new_types', NewTypeController::class)->names('new_types');
Route::apiResource('persons', PersonController::class)->names('persons');
Route::apiResource('projects', ProjectController::class)->names('projects');
Route::apiResource('positions', PositionController::class)->names('positions');
