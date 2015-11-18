<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * Frontend Auth
 */
Route::get('/', 'AuthController@getLogin');
Route::get('auth/login', 'AuthController@getLogin');
Route::post('auth/login', ['as' => 'auth.login', 'uses' => 'AuthController@postLogin']);
Route::get('auth/getEmail', 'PasswordController@getEmail');
Route::post('auth/postEmail', ['as' => 'auth.password.email', 'uses' => 'PasswordController@postEmail']);
Route::get('auth/getReset/{token}', 'PasswordController@getReset');
Route::post('auth/postReset', ['as' => 'auth.password.reset', 'uses' => 'PasswordController@postReset']);
Route::get('auth/logout', 'AuthController@getLogout');


/**
 * Admin Auth
 */
Route::get('admin', 'Admin\AuthController@getLogin');
Route::get('admin/auth/login', 'Admin\AuthController@getLogin');
Route::post('admin/auth/login', ['as' => 'admin.login', 'uses' => 'Admin\AuthController@postLogin']);
Route::get('admin/auth/register', ['uses' => 'Admin\AuthController@getRegister']);
Route::post('admin/auth/register', ['as' => 'admin.register', 'uses' => 'Admin\AuthController@postRegister']);
Route::get('admin/auth/getEmail', 'PasswordController@getEmail');
Route::post('admin/auth/postEmail', ['as' => 'auth.password.email', 'uses' => 'PasswordController@postEmail']);
Route::get('admin/auth/getReset/{token}', 'PasswordController@getReset');
Route::post('admin/auth/postReset', ['as' => 'auth.password.reset', 'uses' => 'PasswordController@postReset']);
Route::get('admin/auth/logout', 'Admin\AuthController@getLogout');


/**
 * Frontend Dashboard
 */
Route::get('dashboard', [
    'as'         => 'dashboard',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator', 'user'],
    'uses'       => 'DashboardController@index'
]);

Route::get('dashboard/view/{plan_id}/{user_id}', [
    'as'         => 'dashboard.plan.view',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator', 'user'],
    'uses'       => 'DashboardController@view'
]);

Route::post('dashboard/save', [
    'as'         => 'dashboard.plan.save',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator', 'user'],
    'uses'       => 'DashboardController@save'
]);


/**
 * Admin Dashboard
 */
Route::get('admin/dashboard', [
    'as'         => 'admin.dashboard',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'Admin\DashboardController@index'
]);

Route::get('admin/dashboard/view/{plan_id}/{user_id}', [
    'as'         => 'admin.dashboard.plan.view',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'Admin\DashboardController@view'
]);


/**
 * Plans
 */
Route::get('plan/view/{id}', ['as' => 'plan.view', 'uses' => 'PlansController@view']);
Route::get('plan/build', [
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'PlansController@build'
]);
Route::get('plan/review', 'PlansController@review');
Route::get('plan/response/{id}', ['as' => 'plan.response', 'uses' => 'PlansController@response']);
Route::get('plan/viewAll', 'PlansController@viewAll');


Route::post('plan/saveUserResponse', ['as' => 'plan.save.user.response', 'uses' => 'PlansController@saveUserResponse']);
Route::post('plan/save', 'PlansController@save');
Route::resource('plan', 'PlansController');


/**
 * Tickets
 */
Route::get('ticket/build', [
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'TicketsController@build'
]);
Route::get('ticket/respond', 'TicketsController@respond');
Route::resource('ticket', 'TicketsController');


/**
 * Testers
 */
Route::get('tester/build', [
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'TesterController@build'
]);
Route::resource('browser-tester', 'TesterController');