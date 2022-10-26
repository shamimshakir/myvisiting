<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MeetingController; 
use App\Http\Controllers\VisitorController;  
use App\Http\Controllers\PermissionController;  
use App\Http\Controllers\CompanyController;  
use App\Http\Controllers\MeetingTypeController;  
use App\Http\Controllers\VisitorTypeController;  
use App\Http\Middleware\CheckLogin;
use App\Http\Middleware\CheckLogged;

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


Route::middleware(['checklogged'])->group(function () {
    Route::post('/login', [UserController::class, 'loginCheck'])->name('logincheck');
    Route::get( '/login', [UserController::class, 'loginPage'] )->name('loginpage');
});

// Clear application cache:
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return 'Application cache has been cleared';
});
//Clear route cache:
Route::get('/route-cache', function() {
	Artisan::call('route:cache');
    return 'Routes cache has been cleared';
});
//Clear config cache:
Route::get('/config-cache', function() {
 	Artisan::call('config:cache');
 	return 'Config cache has been cleared';
}); 
// Clear view cache:
Route::get('/view-clear', function() {
    Artisan::call('view:clear');
    return 'View cache has been cleared';
});

Route::get('/clear-all', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:cache');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('event:clear');
    Artisan::call('optimize:clear');
    Artisan::call('queue:clear');
    Artisan::call('schedule:clear-cache');
    return 'all things have been cleared';
});

Route::middleware(['checklogin'])->group(function () {
    Route::resources([
        'profiles' => ProfileController::class,
        'users' => UserController::class,
        'designations' => DesignationController::class,
        'departments' => DepartmentController::class,
        'meetings' => MeetingController::class,
        'visitors' => VisitorController::class, 
        'visitor_types' => VisitorTypeController::class, 
        'meeting_types' => MeetingTypeController::class, 
    ]);
    Route::get('/', [HomeController::class, 'index'])->name('dashboard-home');
    Route::get('/get_countries', [HomeController::class, 'getCountries'])->name('get-countries');  
    Route::get('/get_profile_list', [ProfileController::class, 'getHtmlData'])->name('get-profile-list');
    Route::get('/get_meeting_type_list', [MeetingTypeController::class, 'getHtmlData'])->name('get_meeting_type_list');
    Route::get('/get_visitor_type_list', [VisitorTypeController::class, 'getHtmlData'])->name('get_visitor_type_list');
    Route::get('/get_designation_list', [DesignationController::class, 'getHtmlData'])->name('get-designation-list');
    Route::get('/get_department_list', [DepartmentController::class, 'getHtmlData'])->name('get-department-list');
    Route::get('/get_user_list', [UserController::class, 'getHtmlData'])->name('get-user-list');
    Route::post('/get_meeting_list', [MeetingController::class, 'getMeetingList'])->name('get_meeting_list');
    Route::post('/get_front_desk_meetings', [MeetingController::class, 'getFrontDeskMeetings'])->name('get_front_desk_meetings');
    Route::get('/get_visitor_list', [VisitorController::class, 'getHtmlData'])->name('get-visitor-list');
    Route::get('/get_cities', [HomeController::class, 'getCities'])->name('get-cities');
    Route::get('/log_user_level', [HomeController::class, 'logUserLevel'])->name('log_user_level');
    Route::get('/get_thanas', [HomeController::class, 'getThanas'])->name('get-thanas');
    Route::get('/get_departments', [HomeController::class, 'getDepartments'])->name('get-departments');
    Route::get('/get_visitors', [HomeController::class, 'getVisitors'])->name('get_visitors');
    Route::get('/get_designations', [HomeController::class, 'getDesignations'])->name('get-designations');
    Route::get('/get_profiles', [HomeController::class, 'getProfiles'])->name('get-profiles');
    Route::get('/get_nodes', [HomeController::class, 'getNodes'])->name('get_nodes');
    Route::get('/get_visitor_types', [HomeController::class, 'getVisitorTypes'])->name('get_visitor_types');
    Route::get('/get_meeting_types', [HomeController::class, 'getMeetingTypes'])->name('get_meeting_types');
    Route::get('/get_cities_by_id/{id}', [HomeController::class, 'getCitiesById'])->name('get-cities-by-id');
    Route::get('/get_thanas_by_id/{id}', [HomeController::class, 'getThanasById'])->name('get-thanas-by-id');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('/meetings/change_status', [MeetingController::class, 'changeStatus'])->name('change_meeting_status'); 
    Route::post('/permission/save', [PermissionController::class, 'store'])->name('permission_save');
    Route::post('/setup/gobal', [UserController::class, 'globalSetup'])->name('global_setup');
    Route::get('/change_password', [UserController::class, 'changePassword'])->name('change_password');
    Route::post('/update_my_password', [UserController::class, 'updateMyPassword'])->name('update_my_password');
    Route::get('/company', [CompanyController::class, 'edit'])->name('company_by_id');
    Route::get('/company/view', [CompanyController::class, 'companyGetbyId'])->name('company_view_by_id');
    Route::put('/company/update', [CompanyController::class, 'update'])->name('company_update');
    Route::get('/front_desk', [MeetingController::class, 'frontDesk'])->name('front_desk');
    Route::post('/authorize_to_checkin', [MeetingController::class, 'AuthorizeToCheckIn'])->name('authorize_to_checkin');
});