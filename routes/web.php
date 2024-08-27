<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\CompanyAuthController;
use App\Http\Controllers\QuestionaireController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\DocController;
use App\Http\Controllers\TranslateController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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

Route::get('/', function () {
    return view('index');
})->middleware('check.locale');

Auth::routes();

Route::get('/clear/route', '\App\Http\Controllers\ConfigController@clearRoute');

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/home', [BaseController::class, 'index'])->middleware('check.locale')->name('home');

Route::post('company-register', [CompanyAuthController::class, 'register'])->middleware('check.locale')->name('company_register_store'); 

Route::get('/company/register', [CompanyAuthController::class, 'register_page'])->middleware('check.locale')->name('company_register_page'); 

//вакансии
Route::get('/create', [WorkController::class, 'create'])->middleware('check.locale')->name('create_work');

Route::get('/work/template/{workId}', [WorkController::class, 'template'])->middleware('check.locale')->name('template_work');

//магазин кандидатов
Route::get('/work/shop/{slug}', [WorkController::class, 'shop'])->middleware('check.locale')->name('shop_work');
//редактирование
Route::get('/work/edit/{workId}', [WorkController::class, 'edit'])->middleware('check.locale')->name('edit_work');

//опросник
Route::get('/q/{slug}', [QuestionaireController::class, 'create'])->middleware('check.locale')->name('createQuestionaire');

//файлы картинки
Route::post('/upload/image', [FileUploadController::class, 'upload'])->name('upload_image');

Route::get('/document', [DocController::class, 'document'])->middleware('check.locale')->name('document');
Route::get('/oferta', [DocController::class, 'oferta'])->middleware('check.locale')->name('oferta');
Route::get('/policy', [DocController::class, 'policy'])->middleware('check.locale')->name('policy');
Route::get('/instruction', [DocController::class, 'instruction'])->middleware('check.locale')->name('instruction');

//lang
Route::get('/js/lang.js', [TranslateController::class, 'assets_lang'])->name('assets.lang');
Route::get('set-locale/{locale}', [TranslateController::class, 'set_locale'])->name('locale.setting');

Route::get('admin-login', [AdminAuthController::class, 'login'])->name('admin_login'); 
Route::get('admin-logout', [AdminAuthController::class, 'logout'])->name('admin_logout'); 
Route::post('admin-post-login', [AdminAuthController::class, 'post_login'])->name('admin_post_login');

// Password Reset
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

//admin
Route::group(
[
    'prefix' => 'admin',
    'namespace' => 'admin', 
    'middleware' => 'admin',
],
function()
{

    Route::get('home', [AdminController::class, 'companies'])->name('admin_home');

    Route::get('selects', [AdminController::class, 'selects'])->name('admin_selects');

    Route::get('companies', [AdminController::class, 'companies'])->name('admin_companies');

    Route::get('vacancies', [AdminController::class, 'vacancies'])->name('admin_vacancies');

    Route::get('/company/{companyId}', [AdminController::class, 'company'])->name('admin_company');

    Route::get('/work/{workId}', [AdminController::class, 'work'])->name('admin_work');

    Route::get('/work/export/{itemId}', [ExportController::class, 'export_work_doc'])->name('admin_export_work');
    Route::get('/work/delete/{itemId}', [WorkController::class, 'destroy'])->name('admin_delete_work');

    Route::get('/questionaire/export/{itemId}', [ExportController::class, 'export_questionaire_doc'])->name('admin_export_questionaire');
    Route::get('/questionaire/delete/{itemId}', [QuestionaireController::class, 'destroy'])->name('admin_delete_questionaire');

    //пополнить счет компании
    Route::get('/company/deposit/{userId}', [DepositController::class, 'create'])->name('admin_deposit_create'); 
    Route::post('/company/deposit/store', [DepositController::class, 'store'])->name('admin_deposit_store');

    //классификаторы
    Route::get('/cities', [AdminController::class, 'cities'])->name('admin_cities');
    Route::get('/regions', [AdminController::class, 'regions'])->name('admin_regions');
    Route::get('/educations', [AdminController::class, 'educations'])->name('admin_educations');
    Route::get('/type-educations', [AdminController::class, 'type_educations'])->name('admin_type_educations');
    Route::get('/specializations', [AdminController::class, 'specializations'])->name('admin_specializations');
    Route::get('/skills', [AdminController::class, 'skills'])->name('admin_skills');
    Route::get('/languages', [AdminController::class, 'languages'])->name('admin_languages');
    Route::get('/cars', [AdminController::class, 'cars'])->name('admin_cars');
    Route::get('/car-licences', [AdminController::class, 'car_licences'])->name('admin_car_licences');
    Route::get('/language-levels', [AdminController::class, 'language_levels'])->name('admin_language_levels');
    Route::get('/employments', [AdminController::class, 'employments'])->name('admin_employments');
    Route::get('/degrees', [AdminController::class, 'degrees'])->name('admin_degrees');
    Route::get('/skill-levels', [AdminController::class, 'skill_levels'])->name('admin_skill_levels');
    Route::get('/type-contracts', [AdminController::class, 'type_contracts'])->name('admin_type_contracts');
    Route::get('/facilitations', [AdminController::class, 'facilitations'])->name('admin_facilitations');
    Route::get('/currencies', [AdminController::class, 'currencies'])->name('admin_currencies');
    Route::get('/personals', [AdminController::class, 'personals'])->name('admin_personals');

});