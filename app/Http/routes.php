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
 * Auth
 */
Route::get('/', 'AuthController@getLogin');
Route::get('auth/login', ['as' => 'auth.login', 'uses' => 'AuthController@getLogin']);
Route::post('auth/postLogin', ['as' => 'auth.post.login', 'uses' => 'AuthController@postLogin']);
Route::get('auth/register', ['as' => 'auth.register','uses' => 'AuthController@getRegister']);
Route::post('auth/postRegister', ['as' => 'auth.post.register', 'uses' => 'AuthController@postRegister']);
Route::get('password/getEmail', ['as' =>'password.email', 'uses' => 'PasswordController@getEmail']);
Route::post('password/postEmail', ['as' => 'password.post.email', 'uses' => 'PasswordController@postEmail']);
Route::get('password/getReset/{token}', ['as' =>'password.reset', 'uses' => 'PasswordController@getReset']);
Route::post('password/postReset', ['as' => 'password.post.reset', 'uses' => 'PasswordController@postReset']);
Route::get('auth/logout', ['as' => 'auth.logout', 'uses' => 'AuthController@getLogout']);

/**
 * Dashboard
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

Route::get('dashboard/respond/{plan_id}/{user_id}', [
    'as'         => 'dashboard.plan.respond',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator', 'user'],
    'uses'       => 'DashboardController@respond'
]);

Route::post('dashboard/save', [
    'as'         => 'dashboard.plan.save',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator', 'user'],
    'uses'       => 'DashboardController@save'
]);


/**
 * Plans
 */
Route::get('plan/view/{id}', ['as' => 'plan.view', 'uses' => 'PlansController@view']);
Route::get('plan/build', [
    'as'         => 'plan.build',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'PlansController@build'
]);
Route::get('plan/review', ['as' => 'plan.review', 'uses' => 'PlansController@review']);
Route::get('plan/viewAll', ['as' => 'plan.view.all', 'uses' => 'PlansController@viewAll']);


Route::post('plan/saveUserResponse', ['as' => 'plan.save.user.response', 'uses' => 'PlansController@saveUserResponse']);
Route::post('plan/save', ['as' => 'plan.save', 'uses' => 'PlansController@save']);
Route::resource('plan', 'PlansController');


/**
 * Tickets
 */
Route::get('ticket/build', [
    'as'         => 'ticket.build',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'TicketsController@build'
]);
Route::get('ticket/response', ['as' => 'plan.response', 'uses' => 'TicketsController@response']);
Route::resource('ticket', 'TicketsController');


/**
 * Testers
 */
Route::get('tester/build', [
    'as'         => 'tester.build',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'TesterController@build'
]);
Route::resource('browser-tester', 'TesterController');