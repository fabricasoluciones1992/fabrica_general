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
use App\Http\Controllers\MedicalhistoriesController;
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
define("URL", "/{proj_id}/");

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
Route::post('/register'.URL, [AuthController::class, 'register'])->name('register');
Route::post('/login'.URL, [AuthController::class, 'login'])->name('login');
Route::post('/logout'.URL, [AuthController::class, 'logout'])->name('logout');

// Route::middleware(['auth:sanctum'])->group(function() {
    Route::resource('access'.URL, AccessController::class)->names('access')->parameter('','access');
    Route::resource('areas'.URL, AreaController::class)->names('areas')->parameter('','areas');
    Route::resource('contacts'.URL, ContactController::class)->names('contacts')->parameter('','contacts');
    Route::resource('doctypes'.URL, DocTypesController::class)->names('doctypes')->parameter('','doctypes');
    Route::resource('eps'.URL, EpsController::class)->names('eps')->parameter('','eps');
    Route::resource('genders'.URL, GenderController::class)->names('genders')->parameter('','genders');
    Route::resource('localities'.URL, LocalityController::class)->names('localities')->parameter('','localities');
    Route::resource('news'.URL, NewsController::class)->names('news')->parameter('','news');
    Route::resource('new_types'.URL, NewTypeController::class)->names('new_types')->parameter('','new_types');
    Route::resource('persons'.URL, PersonController::class)->names('persons')->parameter('','persons');
    Route::resource('projects'.URL, ProjectController::class)->names('projects')->parameter('','projects');
    Route::resource('positions'.URL, PositionController::class)->names('positions')->parameter('','positions');
    Route::resource('multiculturalisms'.URL, MulticulturalismsController::class)->names('multiculturalisms')->parameter('','multiculturalisms');
    Route::resource('civilstates'.URL, CivilStatesController::class)->names('civilstates')->parameter('','civilstates');
    Route::resource('relationships'.URL, RelationshipsController::class)->names('relationships')->parameter('','relationships');
    Route::resource('disaeses'.URL, DiseasesController::class)->names('disaeses')->parameter('','disaeses');
    Route::resource('mails'.URL, MailsController::class)->names('mails')->parameter('','mails');
    Route::resource('telephones'.URL, TelephonesController::class)->names('telephones')->parameter('','telephones');
    Route::resource('medicalHistories'.URL, MedicalhistoriesController::class)->names('medicalHistories')->parameter('','medicalHistories');
//  



