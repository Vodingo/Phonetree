<?php

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

use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/access-denied', 'SecurityController@index')->name('access-denied');

// Home page routes
Route::get('/', 'HomeController@index')->name('home')->middleware('can:web_view_home');
Route::get('/get-home-sessions-select-list', 'HomeController@getSessionSelectList')->name('home_session_select_list')->middleware('can:web_home_session_select_list');
Route::get('/get-home-supervisor-select-list', 'HomeController@getSupervisorSelectList')->name('home_supervisor_select_list')->middleware('can:web_home_supervisor_select_list');
Route::get('/get-home-register-status-select-list', 'HomeController@getStatusSelectList')->name('home_register_status_select_list')->middleware('can:web_home_register_status_select_list');
Route::post('/home/register/save', 'HomeController@registerStaffAttendance')->name('home_register_save')->middleware('can:web_home_register_save');
Route::get('/home/supervisors/staff', 'HomeController@getSupervisedStaff')->middleware('can:web_home_supervised_staff');

// Dashboard page routes
Route::get('/management', 'Admin\ManagementDashboardController@index')->name('management-dashboard')->middleware('can:web_dashboard_home');
Route::get('/management/summary/{session}', 'Admin\ManagementDashboardController@summary')->middleware('can:web_dashboard_summary');
Route::get('/management/accounted', 'Admin\ManagementDashboardController@getAccountedStaff')->middleware('can:web_dashboard_view_accounted_staff');
Route::get('/management/unaccounted', 'Admin\ManagementDashboardController@getUnaccountedStaff')->middleware('can:web_dashboard_view_unaccounted_staff');
Route::get('/management/notcontacted', 'Admin\ManagementDashboardController@getStaffNotContacted')->middleware('can:web_dashboard_view_staff_not_contacted');
Route::get('/management/supervisors-summary', 'Admin\ManagementDashboardController@getSupervisorsSummary')->middleware('can:web_dashboard_view_supervisors_summary');

Route::get('/get-dashboard-sessions-select-list', 'Admin\ManagementDashboardController@getSessionSelectList')->name('dashboard_session_select_list')->middleware('can:web_dashboard_session_select_list');
Route::get('/management/department-select-list', 'Admin\ManagementDashboardController@getDepartmentSelectList')->name('dashboard_department_select_list')->middleware('can:web_dashboard_department_select_list');
Route::get('/management/unit-select-list', 'Admin\ManagementDashboardController@getUnitSelectList')->name('dashboard_unit_select_list')->middleware('can:web_dashboard_unit_select_list');
Route::get('/management/supervisors-select-list', 'Admin\ManagementDashboardController@getSupervisorSelectList')->name('dashboard_supervisor_select_list')->middleware('can:web_dashboard_supervisor_select_list');


Route::prefix('settings')->group(function () {

    // Roll Call session routes
    Route::get('/sessions', 'Admin\RollCallSessionController@view')->name('sessions-settings')->middleware('can:web_sessions_home');
    Route::get('/sessions/list', 'Admin\RollCallSessionController@getAllSessions')->middleware('can:web_sessions_list');
    Route::post('/sessions/save', 'Admin\RollCallSessionController@store')->middleware('can:web_sessions_create_roll_call');
    Route::post('/sessions/update', 'Admin\RollCallSessionController@update')->middleware('can:web_sessions_update_roll_call');
    Route::post('/sessions/complete', 'Admin\RollCallSessionController@complete')->middleware('can:web_sessions_complete_roll_call');
    Route::post('/sessions/delete', 'Admin\RollCallSessionController@destroy')->middleware('can:web_sessions_delete_roll_call');
    Route::get('/sessions/filter', 'Admin\RollCallSessionController@filterSessions')->middleware('can:web_sessions_filter_roll_call');

    // Staff List routes
    Route::get('/staff', 'Admin\StaffListController@view')->name('staff')->middleware('can:web_staff_home');//web_staff_home
    Route::get('/staff-list', 'Admin\StaffListController@index')->name('staff-list')->middleware('can:web_staff_list');//web_staff_list
    Route::get('/staff/filter', 'Admin\StaffListController@filter')->name('staff-filter')->middleware('can:web_staff_filter');
    Route::post('/staff/upload', 'Admin\StaffListController@upload')->middleware('can:web_staff_upload');
    Route::post('/staff/save', 'Admin\StaffListController@store')->middleware('can:web_staff_create');
    Route::post('/staff/{staffList}/update', 'Admin\StaffListController@edit')->middleware('can:web_staff_update');
    Route::post('/staff/{staffList}/delete', 'Admin\StaffListController@destroy')->middleware('can:web_staff_delete');
    Route::get('/staff/department-select-list', 'Admin\StaffListController@getDepartmentSelectList')->middleware('can:web_staff_department_select_list');
    Route::get('/staff/unit-select-list', 'Admin\StaffListController@getUnitSelectList')->middleware('can:web_staff_unit_select_list');
    Route::get('/staff/supervisors-select-list', 'Admin\StaffListController@getSupervisorSelectList')->middleware('can:web_staff_supervisor_select_list');

    // Supervisors Settings Routes
    Route::get('/supervisors', 'Admin\SupervisorController@view')->name('supervisors')->middleware('can:web_supervisors_home');
    Route::get('/supervisors-list', 'Admin\SupervisorController@index')->name('supervisors-list')->middleware('can:web_supervisors_list');
    Route::get('/supervisors-staff-list', 'Admin\SupervisorController@staff')->name('supervisors-staff-list')->middleware('can:web_supervisors_supervised_staff_list');
    Route::get('/supervisors-staff/{supervisor}', 'Admin\SupervisorController@staffList')->name('supervisors-staff')->middleware('can:web_supervisors_view_staff_by_supervisor');
    Route::post('/supervisors/save', 'Admin\SupervisorController@store')->middleware('can:web_supervisor_add');
    Route::post('/supervisors/{staffList}/update', 'Admin\SupervisorController@edit')->middleware('can:web_supervisor_update');
    Route::post('/supervisors/{supervisor}/delete', 'Admin\SupervisorController@destroy')->middleware('can:web_supervisor_delete');
    Route::post('/supervisors/staff/save', 'Admin\SupervisorController@storeSupervisorStaff')->middleware('can:web_supervisor_save');
    Route::post('/supervisors/staff/{staff}/delete', 'Admin\SupervisorController@destroySupervisorStaff')->middleware('can:web_supervisors_delete_supervised_staff');
    Route::get('/supervisors/supervised-staff', 'Admin\SupervisorController@getSupervisedStaff')->middleware('can:web_supervisors_supervised_staff');
    Route::get('/supervisors/department-select-list', 'Admin\SupervisorController@getDepartmentSelectList')->middleware('can:web_supervisor_department_select_list');
    Route::get('/supervisors/unit-select-list', 'Admin\SupervisorController@getUnitSelectList')->middleware('can:web_supervisor_unit_select_list');
    Route::get('/supervisors/supervisors-select-list', 'Admin\SupervisorController@getSupervisorSelectList')->middleware('can:web_supervisor_supervisor_select_list');

    // Departments Settings Routes
    Route::get('/departments', 'Admin\DepartmentController@view')->name('departments')->middleware('can:web_department_home');
    Route::get('/departments-list', 'Admin\DepartmentController@index')->name('departments-list')->middleware('can:web_department_list');
    Route::post('/departments', 'Admin\DepartmentController@store')->middleware('can:web_department_create');
    Route::post('/departments/{department}/update', 'Admin\DepartmentController@update')->middleware('can:web_department_update');
    Route::post('/departments/{department}/delete', 'Admin\DepartmentController@destroy')->middleware('can:web_department_delete');

    // Units Settings Routes
    Route::get('/units', 'Admin\UnitController@view')->name('units')->middleware('can:web_unit_home');
    Route::get('/units-list', 'Admin\UnitController@index')->name('units-list')->middleware('can:web_unit_list');
    Route::post('/units', 'Admin\UnitController@store')->middleware('can:web_unit_create');
    Route::post('/units/{unit}/update', 'Admin\UnitController@update')->middleware('can:web_unit_update');
    Route::post('/units/{unit}/delete', 'Admin\UnitController@destroy')->middleware('can:web_unit_delete');

    // Permissions Settings Routes
    Route::get('/permissions', 'Admin\PermissionController@view')->name('permissions')->middleware('can:web_permissions_home');
    Route::get('/permissions/list', 'Admin\PermissionController@index')->middleware('can:web_permissions_list');
    Route::post('/permissions/save', 'Admin\PermissionController@store')->middleware('can:web_permissions_create');
    Route::post('/permissions/{permission}/update', 'Admin\PermissionController@update')->middleware('can:web_permissions_update');
    Route::post('/permissions/{permission}/delete', 'Admin\PermissionController@destroy')->middleware('can:web_permissions_delete');

    // Roles Settings Routes
    Route::get('/roles', 'Admin\RoleController@view')->name('roles')->middleware('can:web_roles_home');
    Route::get('/roles/list', 'Admin\RoleController@index')->middleware('can:web_roles_list');
    Route::post('/roles/save', 'Admin\RoleController@store')->middleware('can:web_roles_create');
    Route::post('/roles/{role}/update', 'Admin\RoleController@update')->middleware('can:web_roles_update');
    Route::post('/roles/{role}/delete', 'Admin\RoleController@destroy')->middleware('can:web_roles_delete');
    Route::get('/roles/{role}/permissions', 'Admin\RoleController@permissions')->middleware('can:web_roles_view_role_permissions');
    Route::get('/roles/{role}/permissions/edit', 'Admin\RoleController@assignedPermissions')->middleware('can:web_roles_edit_role_permissions');
    Route::post('/roles/{role}/permissions/save', 'Admin\RoleController@assignPermissions')->middleware('can:web_roles_assign_role_permissions');

    // Manage Users Settings Routes
    Route::get('/users', 'Admin\UserController@index')->name('users-settings')->middleware('can:web_users_home');
    Route::post('/users/register', 'Admin\UserController@store')->middleware('can:web_users_create');
    Route::post('/users/update', 'Admin\UserController@update')->middleware('can:web_users_update');
    Route::post('/users/deactivate', 'Admin\UserController@deactivate')->middleware('can:web_users_deactivate');
    Route::get('/registered-users', 'Admin\UserController@registered')->name('registered-users')->middleware('can:web_users_registered');
    Route::get('/unregistered-users', 'Admin\UserController@unregistered')->name('unregistered-users')->middleware('can:web_users_unregistered');
});

Route::get('/profile', 'Admin\UserController@profile')->name('user-profile')->middleware('can:web_profile_view');
Route::post('/update-profile', 'Admin\UserController@updateProfile')->name('update-profile')->middleware('can:web_profile_update');

Route::prefix('mobile')->group(function () {
    Route::get('/login', 'Mobile\LoginController@index')->name('mobile-login');
    Route::get('/entry-form/{session}/{staffId}', 'Mobile\DataEntryController@showEntryForm')->name('mobile-entry-form')->middleware('can:mobile_home_view_form');
    Route::post('/register/save', 'Admin\RegisterController@storeEntry')->name('mobile-update-register')->middleware('can:mobile_home_save_status');
    Route::get('/index', 'Mobile\DataEntryController@index')->name('mobile-index')->middleware('can:mobile_dashboard_home');
    Route::get('/dashboard', 'Mobile\DashboardController@index')->name('mobile-dashboard')->middleware('can:mobile_dashboard_view');
    Route::get('/accounted', 'Mobile\DashboardController@accounted')->name('mobile-accounted-staff')->middleware('can:mobile_dashboard_view_accounted_staff');
    Route::get('/unaccounted', 'Mobile\DashboardController@unAccounted')->name('mobile-unaccounted-staff')->middleware('can:mobile_dashboard_view_unaccounted_staff');
    Route::get('/not-contacted', 'Mobile\DashboardController@notContacted')->name('mobile-staff-not-contacted')->middleware('can:mobile_dashboard_view_staff_not_contacted');

});

 Route::post('/register/save', 'Admin\RegisterController@store')->name('update-register')->middleware('can:update_register');
 Route::get('/register/{session}', 'Admin\RegisterController@index')->name('list-register')->middleware('can:view_registered_users');
// Route::get('/status', 'Admin\StatusController@index')->middleware('can:get_statuses');
// Route::get('/phone-tree', 'Admin\DepartmentController@view')->name('phone-tree')->middleware('can:manage_department');
// Route::get('/sessions', 'Admin\RollCallSessionController@index')->name('sessions-list')->middleware('can:web_view_home')->middleware('can:view_roll_call_sessions');
// Route::get('/register/supervisors', 'HomeController@getSupervisors')->middleware('can:home_supervisors_select_list');