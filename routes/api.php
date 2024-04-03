<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CareerTypesController;
use App\Http\Controllers\CivilStatesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DiseasesController;
use App\Http\Controllers\DocTypesController;
use App\Http\Controllers\EpsController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\HistoryCarrerController;
use App\Http\Controllers\HistoryPromotionController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\LearningObjectsController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\MailsController;
use App\Http\Controllers\MedicalhistoriesController;
use App\Http\Controllers\MonetaryStatesController;
use App\Http\Controllers\MulticulturalismsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewTypeController;
use App\Http\Controllers\PayTimesController;
use App\Http\Controllers\PayTypesController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonTypesController;
use App\Http\Controllers\PhaseController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProcessTypesController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RelationshipsController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TelephonesController;
use App\Http\Controllers\VinculationTypeController;
use App\Models\Contact_Companies_Types;
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
Route::post('password/emergency'.URL, [PersonController::class, 'passwordEmergency'])->name('password.emergency');

Route::middleware(['auth:sanctum'])->group(function() {
    Route::resource('access'.URL, AccessController::class)->names('access')->parameter('','access');
    Route::resource('areas'.URL, AreaController::class)->names('areas')->parameter('','areas');
    Route::resource('contacts'.URL, ContactController::class)->names('contacts')->parameter('','contacts');
    Route::resource('doc/types'.URL, DocTypesController::class)->names('doc.types')->parameter('','doc_types');
    Route::resource('eps', EpsController::class)->names('eps')->parameter('','eps');
    Route::resource('genders'.URL, GenderController::class)->names('genders')->parameter('','genders');
    Route::resource('localities'.URL, LocalityController::class)->names('localities')->parameter('','localities');
    Route::resource('multiculturalisms'.URL, MulticulturalismsController::class)->names('multiculturalisms')->parameter('','multiculturalisms');
    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/{type}/{proj_id}/{date}', [NewsController::class, 'show'])->name('news.show');
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
    Route::resource('student'.URL, StudentController::class)->names('student')->parameter('','student');
    Route::get('profile'.URL, [PersonController::class, 'profile'])->name('profile');
    Route::post('update/password'.URL, [PersonController::class, 'update_password'])->name('update.password');
    Route::get('persons/filtred'.URL.'{column}/{data}', [PersonController::class, 'filtredfortypeperson'])->name('filtrar.personas');
    Route::post('persons/document'.URL, [PersonController::class, 'viewForDocument'])->name('view.for.document');
    Route::get('last/persons'.URL, [PersonController::class, 'lastPersons'])->name('last.persons');
    Route::resource('activity'.URL, ActivityController::class)->names('activity')->parameter('','activity');
    Route::resource('career'.URL, CareerController::class)->names('career')->parameter('','career');
    Route::resource('career/types'.URL, CareerTypesController::class)->names('career.types')->parameter('','career_types');
    Route::resource('contact/companies/type'.URL, Contact_Companies_Types::class)->names('contact.companies.types')->parameter('','contact_companies_types');
    Route::resource('contract/types'.URL, ContractTypeController::class)->names('contract.types')->parameter('','contract_types');
    Route::resource('country'.URL, CountryController::class)->names('country')->parameter('','country');
    Route::resource('History/career'.URL, HistoryCarrerController::class)->names('')->parameter('','history_career');
    Route::resource('history/promotion'.URL, HistoryPromotionController::class)->names('history.promotion')->parameter('','history_promotion');
    Route::resource('industry'.URL, IndustryController::class)->names('industry')->parameter('','industry');
    Route::resource('learning/objects'.URL, LearningObjectsController::class)->names('learning.objects')->parameter('','learning_objects');
    Route::resource('monetary/states'.URL, MonetaryStatesController::class)->names('monetary.states')->parameter('','money_states');
    Route::resource('pay/times'.URL, PayTimesController::class)->names('pay.times')->parameter('','pay_times');
    Route::resource('pay/types'.URL, PayTypesController::class)->names('pay.types')->parameter('','pay_types');
    Route::resource('period'.URL, PeriodController::class)->names('period')->parameter('','period');
    Route::resource('phase'.URL, PhaseController::class)->names('phase')->parameter('','phase');
    Route::resource('process/types'.URL, ProcessTypesController::class)->names('process.types')->parameter('','process_types');
    Route::resource('promotion'.URL, PromotionController::class)->names('promotion')->parameter('','promotion');
    Route::resource('size'.URL, SizeController::class)->names('size')->parameter('','size');
    Route::resource('vinculation/type'.URL, VinculationTypeController::class)->names('vinculation.type')->parameter('','vinculation_type');
});