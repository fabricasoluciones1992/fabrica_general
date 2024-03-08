<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CivilStatesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Controller;
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
use App\Http\Controllers\PersonTypesController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RelationshipsController;
use App\Http\Controllers\TelephonesController;
use App\Models\multiculturalisms;
use App\Models\relationships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

define("URL", "/{proj_id}/{use_id}/");
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
Route::post('/register/{use_id}', [AuthController::class, 'register'])->name('register');
Route::post('/login/{proj_id}', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('reset/password', [PersonController::class, 'reset_password'])->name('reset.password');
Route::post('send/email/', [PersonController::class, 'sendEmailReminder'])->name('send.email');

Route::middleware(['auth:sanctum'])->group(function() {
    Route::resource('access'.URL, AccessController::class)->names('access')->parameter('','access');
    Route::resource('areas'.URL, AreaController::class)->names('areas')->parameter('','areas');
    Route::resource('contacts'.URL, ContactController::class)->names('contacts')->parameter('','contacts');
    Route::resource('doc/types'.URL, DocTypesController::class)->names('doc.types')->parameter('','doc_types');
    Route::resource('eps'.URL, EpsController::class)->names('eps')->parameter('','eps');
    Route::resource('genders'.URL, GenderController::class)->names('genders')->parameter('','genders');
    Route::resource('localities'.URL, LocalityController::class)->names('localities')->parameter('','localities');
    Route::resource('multiculturalisms'.URL, MulticulturalismsController::class)->names('multiculturalisms')->parameter('','multiculturalisms');
    Route::get('news'.URL.'{column}/{data}', [NewsController::class, 'index'])->name('news.index');
    Route::resource('new/types'.URL, NewTypeController::class)->names('new.types')->parameter('','new_types');
    Route::resource('persons/types'.URL, PersonTypesController::class)->names('persons.types')->parameter('','persons_types');
    Route::resource('persons'.URL, PersonController::class)->names('persons')->parameter('','persons');
    Route::resource('projects'.URL, ProjectController::class)->names('projects')->parameter('','projects');
    Route::resource('positions'.URL, PositionController::class)->names('positions')->parameter('','positions');
    Route::resource('civil/states'.URL, CivilStatesController::class)->names('civil.states')->parameter('','civil_states');
    Route::resource('relationships'.URL, RelationshipsController::class)->names('relationships')->parameter('','relationships');
    Route::resource('diseases'.URL, DiseasesController::class)->names('diseases')->parameter('','diseases');
    Route::resource('mails'.URL, MailsController::class)->names('mails')->parameter('','mails');
    Route::resource('telephones'.URL, TelephonesController::class)->names('telephones')->parameter('','telephones');
    Route::resource('medical/histories'.URL, MedicalhistoriesController::class)->names('medical.histories')->parameter('','medical_histories');
    Route::get('profile'.URL, [PersonController::class, 'profile'])->name('profile');
    Route::post('update/password'.URL, [PersonController::class, 'update_password'])->name('update.password');
    Route::post('persons/filtred'.URL, [PersonController::class, 'filtredfortypeperson'])->name('filtrar.personas');
    Route::post('persons/document'.URL, [PersonController::class, 'viewForDocument'])->name('view.for.document');
});