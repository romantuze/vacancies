<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\GroupSpecializationController;
use App\Http\Controllers\Api\SpecializationController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\CategorySkillController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\CarLicenceController;
use App\Http\Controllers\Api\EmploymentController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\DegreeController;
use App\Http\Controllers\Api\FacilitationController;
use App\Http\Controllers\Api\SkillLevelController;
use App\Http\Controllers\Api\LanguageLevelController;
use App\Http\Controllers\Api\TypeContractController;
use App\Http\Controllers\Api\PersonalController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\TypeEducationController;
use App\Http\Controllers\Api\WorkController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\SelectController;
use App\Http\Controllers\Api\VacancyController;
use App\Http\Controllers\Api\QuestionnaireController;
use App\Http\Controllers\FileUploadController;


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
/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

/*
Route::middleware('auth:api')->get('/user', function(Request $request) {
    return $request->user();
});
*/

// user
Route::get('/users/{userId}',[UserController::class,'show'])->name('showUser');

//company
Route::post('/companies',[CompanyController::class,'index'])->name('getCompanies');

//company info
Route::get('/company/{companyId}',[CompanyController::class,'show'])->name('getCompany');

//вакансии
//сохранение новой вакансии
Route::post('/work/store', [WorkController::class, 'store'])->name('workStore');
Route::post('/works',[WorkController::class,'index'])->name('getWorks');
Route::post('/works_admin',[WorkController::class,'index_admin'])->name('getWorksAdmin');

//просмотр одной вакансии
Route::get('/works/{workId}',[WorkController::class,'show'])->name('showWork');

//просмотр одной сохранненой вакансии
Route::get('/works/edit/{workId}',[WorkController::class,'edit'])->name('editWork');

//сохранненая вакансия
Route::post('/works/update/{workId}',[WorkController::class,'update'])->name('updateWork');

//смена статусов
Route::post('/work/status',[WorkController::class,'status'])->name('statusWork');

//смена статусов отравить в работу
Route::post('/work/send',[WorkController::class,'send'])->name('sendWork');

//продление вакансии
Route::post('/work/time',[WorkController::class,'time'])->name('timeWork');

//скидка вакансии сохранение
Route::post('/work/sale',[WorkController::class, 'sale'])->name('saleWork');

//подтдвердить оплату
Route::post('/work/pay_confirm',[WorkController::class, 'pay_confirm'])->name('payConfirmWork');

//опросники
Route::get('/questions/{workId}', [QuestionController::class, 'index'])->name('questionaireIndex');
Route::get('/admin/questions/{workId}', [QuestionController::class, 'admin_index'])->name('questionaireAdminIndex');
Route::post('/question/store', [QuestionController::class, 'store'])->name('questionaireStore');
Route::post('/question/check_user', [QuestionController::class, 'check_user'])->name('questionaireCheckUser');

//открытие данных кандидата
Route::post('/question/open', [QuestionController::class, 'open'])->name('questionaireOpen');

//замена кандидата
Route::post('/question/replace', [QuestionController::class, 'replace'])->name('questionaireReplace');

//смена статусов
Route::post('/question/status_now',[QuestionController::class,'status_now'])->name('questionaireStatusNow');

//комментарии
Route::post('/question/update_comment',[QuestionController::class,'update_comment'])->name('questionaireUpdateComment');

//города
Route::post('/countries/store',[CountryController::class,'storeCountry'])->name('storeCountry');
Route::post('/countries/update',[CountryController::class,'updateCountry'])->name('updateCountry');
Route::get('/countries/destroy/{countryId}',[CountryController::class,'destroyCountry'])->name('destroyCountry');
Route::post('/regions/store',[CountryController::class,'storeRegion'])->name('storeRegion');
Route::post('/regions/update',[CountryController::class,'updateRegion'])->name('updateRegion');
Route::get('/regions/destroy/{regionId}',[CountryController::class,'destroyRegion'])->name('destroyRegion');
Route::post('/cities/store',[CountryController::class,'storeCity'])->name('storeCity');
Route::post('/cities/update',[CountryController::class,'updateCity'])->name('updateCity');
Route::get('/cities/destroy/{cityId}',[CountryController::class,'destroyCity'])->name('destroyCity');

Route::get('/countries/all',[CountryController::class,'getCountries'])->name('getCountries');
Route::get('/cities/all',[CountryController::class,'getCitiesAll'])->name('getCities');
Route::get('/regions/all',[CountryController::class,'getRegionsAll'])->name('getRegions');
Route::get('/countries/{countryId}/regions', [CountryController::class, 'getRegions'])->name('getCountriesRegions');
Route::get('/region/{regionId}/cities', [CountryController::class, 'getCities'])->name('getRegionCities');

//образование
Route::get('/educations',[EducationController::class,'index'])->name('getEducations');
Route::post('/educations/store',[EducationController::class,'store'])->name('storeEducations');
Route::delete('/educations/{id}',[EducationController::class,'destroy'])->name('destroyEducations');
Route::post('/educations/update',[EducationController::class,'update'])->name('updateEducations');
Route::get('/type-educations',[TypeEducationController::class,'index'])->name('getTypeEducations');
Route::post('/type-educations/store',[TypeEducationController::class,'store'])->name('storeTypeEducations');
Route::delete('/type-educations/{id}',[TypeEducationController::class,'destroy'])->name('destroyTypeEducations');
Route::post('/type-educations/update',[TypeEducationController::class,'update'])->name('updateTypeEducations');

//языки
Route::get('/languages',[LanguageController::class,'index'])->name('getLanguages');
Route::post('/languages/store',[LanguageController::class,'store'])->name('storeLanguages');
Route::delete('/languages/{id}',[LanguageController::class,'destroy'])->name('destroyLanguages');
Route::post('/languages/update',[LanguageController::class,'update'])->name('updateLanguages');

//языки уровень
Route::get('/language-levels',[LanguageLevelController::class,'index'])->name('getLanguageLevels');
Route::post('/language-levels/store',[LanguageLevelController::class,'store'])->name('storeLanguageLevels');
Route::delete('/language-levels/{id}',[LanguageLevelController::class,'destroy'])->name('destroyLanguageLevels');
Route::post('/language-levels/update',[LanguageLevelController::class,'update'])->name('updateLanguageLevels');

//тип авто
Route::get('/cars',[CarController::class,'index'])->name('getCars');
Route::post('/cars/store',[CarController::class,'store'])->name('storeCars');
Route::delete('/cars/{id}',[CarController::class,'destroy'])->name('destroyCars');
Route::post('/cars/update',[CarController::class,'update'])->name('updateCars');

//специализации
Route::get('/group-specializations',[GroupSpecializationController::class,'index'])->name('getGroupSpecializations');
Route::post('/group-specializations/store',[GroupSpecializationController::class,'store'])->name('storeGroupSpecializations');
Route::get('/specializations/all',[SpecializationController::class,'getSpecializationsAll'])->name('getSpecializationsAll');
Route::get('/specializations/{lang}/{groupSpecializationId}',[SpecializationController::class,'index'])->name('getSpecializations');
Route::post('/specializations/store',[SpecializationController::class,'store'])->name('storeSpecializations');
Route::post('/specializations/update',[SpecializationController::class,'update'])->name('updateSpecializations');
Route::get('/specializations/destroy/{specializationId}',[SpecializationController::class,'destroy'])->name('destroySpecializations');

//Навыки
Route::get('/skills/{lang}',[SkillController::class,'index'])->name('getSkills');
Route::get('/skills/{id}',[SkillController::class,'read'])->name('getSkill');
Route::post('/skills/store',[SkillController::class,'store'])->name('storeSkill');
Route::post('/skills/update',[SkillController::class,'update'])->name('updateSkill');
Route::get('/skills/destroy/{skillId}',[SkillController::class,'destroy'])->name('destroySkill');

//права
Route::get('/car-licences',[CarLicenceController::class,'index'])->name('getCarLicences');
Route::post('/car-licences/store',[CarLicenceController::class,'store'])->name('storeCarLicences');
Route::delete('/car-licences/{id}',[CarLicenceController::class,'destroy'])->name('destroyCarLicences');
Route::post('/car-licences/update',[CarLicenceController::class,'update'])->name('updateCarLicences');

//занятости
Route::get('/employments',[EmploymentController::class,'index'])->name('getEmployments');
Route::post('/employments/store',[EmploymentController::class,'store'])->name('storeEmployments');
Route::delete('/employments/{id}',[EmploymentController::class,'destroy'])->name('destroyEmployments');
Route::post('/employments/update',[EmploymentController::class,'update'])->name('updateEmployments');

//валюта
Route::get('/currencies/{user_id}',[CurrencyController::class,'index'])->name('getCurrencies');
Route::post('/currencies/store',[CurrencyController::class,'store'])->name('storeCurrencies');
Route::delete('/currencies/{id}',[CurrencyController::class,'destroy'])->name('destroyCurrencies');
Route::post('/currencies/update',[CurrencyController::class,'update'])->name('updateCurrencies');

//степень
Route::get('/degrees/{lang}',[DegreeController::class,'index'])->name('getDegrees');
Route::post('/degrees/store',[DegreeController::class,'store'])->name('storeDegrees');
Route::delete('/degrees/{id}',[DegreeController::class,'destroy'])->name('destroyDegrees');

//льготы
Route::get('/facilitations',[FacilitationController::class,'index'])->name('getFacilitations');
Route::post('/facilitations/store',[FacilitationController::class,'store'])->name('storeFacilitations');
Route::delete('/facilitations/{id}',[FacilitationController::class,'destroy'])->name('destroyFacilitations');
Route::post('/facilitations/update',[FacilitationController::class,'update'])->name('updateFacilitations');

//навыки уровень
Route::get('/skill-levels',[SkillLevelController::class,'index'])->name('getSkillLevels');
Route::post('/skill-levels/store',[SkillLevelController::class,'store'])->name('storeSkillLevels');
Route::delete('/skill-levels/{id}',[SkillLevelController::class,'destroy'])->name('destroySkillLevels');
Route::post('/skill-levels/update',[SkillLevelController::class,'update'])->name('updateSkillLevels');

//тип договора
Route::get('/type-contracts',[TypeContractController::class,'index'])->name('getTypeContracts');
Route::post('/type-contracts/store',[TypeContractController::class,'store'])->name('storeTypeContracts');
Route::delete('/type-contracts/{id}',[TypeContractController::class,'destroy'])->name('destroyTypeContracts');
Route::post('/type-contracts/update',[TypeContractController::class,'update'])->name('updateTypeContracts');

//Личностные данные
Route::get('/personals',[PersonalController::class,'index'])->name('getPersonals');
Route::get('/personal/{lang}/{id}',[PersonalController::class,'read'])->name('getPersonalsId');
Route::post('/personals/store',[PersonalController::class,'store'])->name('storePersonals');
Route::delete('/personals/{id}',[PersonalController::class,'destroy'])->name('destroyPersonals');
Route::post('/personals/update',[PersonalController::class,'update'])->name('updatePersonals');

Route::get('/selects/{lang}',[SelectController::class,'index'])->name('getSelects');

Route::middleware('auth:api')->group(function () {

//Внешнее api
Route::resources([
    'vacancies' => VacancyController::class,
    'questionnaires' => QuestionnaireController::class,
]);

//файлы резюме
Route::post('/upload/resume', [FileUploadController::class, 'upload_resume'])->name('upload_resume');

//файлы фото
Route::post('/upload/photo', [FileUploadController::class, 'upload_photo'])->name('upload_photo');

});