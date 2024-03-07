<?php

use App\Http\Controllers\Admin\AccountantController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Middleware\AccountMiddleware;
Route::get('/test',function(){
    workTimeCalc();
});
Route::redirect('/', 'login');
Route::get('register');
Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');


    // accountant turshilt

    Route::get('/csvday', 'CSVDayController@index')->name('CSVDay.index');
    Route::get('/csvday/filter', 'CSVDayController@filter')->name('CSVDay.filter');


    Route::get('/CSV', 'CSVController@index')->name('CSV.index');
    Route::post('/CSV', 'CSVController@download')->name('CSV.download');
    Route::get('/report','ReportController@index')->name('report');
    Route::get('/index', 'AccountantController@index')->name('index');
    Route::get('/index/filter', 'AccountantController@filter')->name('accountant.filter');
    Route::patch('/index', 'AccountantController@getDepartment')->name('accountant.getDepartment');




    // turshilt

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Audit Logs
    Route::resource('audit-logs', 'AuditLogsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Arrival Record
    Route::delete('arrival-records/destroy', 'ArrivalRecordController@massDestroy')->name('arrival-records.massDestroy');
    Route::resource('arrival-records', 'ArrivalRecordController');

    // Departure Record
    Route::delete('departure-records/destroy', 'DepartureRecordController@massDestroy')->name('departure-records.massDestroy');
    Route::resource('departure-records', 'DepartureRecordController');
    Route::post('/departure-records/filter', 'DepartureRecordController@filter' )->name('departure-records.filter');

    // Departmen
    Route::delete('departments/destroy', 'DepartmentController@massDestroy')->name('departments.massDestroy');
    Route::resource('departments', 'DepartmentController');

    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home/omnoh/{year}/{month}', 'HomeController@omnoh')->name('home.omnoh');
    Route::post('/home', 'TimeRecordController@record')->name('time.record');
    Route::put('/home', 'TimeRecordController@record_manual')->name('time.record.manual');

    //shine huudas


    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Arrival Record
    Route::delete('arrival-records/destroy', 'ArrivalRecordController@massDestroy')->name('arrival-records.massDestroy');
    Route::resource('arrival-records', 'ArrivalRecordController');

    // Departure Record
    Route::delete('departure-records/destroy', 'DepartureRecordController@massDestroy')->name('departure-records.massDestroy');
    Route::resource('departure-records', 'DepartureRecordController');

    // Department
    Route::delete('departments/destroy', 'DepartmentController@massDestroy')->name('departments.massDestroy');
    Route::resource('departments', 'DepartmentController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
});