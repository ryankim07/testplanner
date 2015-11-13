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

// Auth
Route::get('auth', 'Auth\AuthController@getLogin');
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');
Route::get('auth/getEmail', 'Auth\PasswordController@getEmail');
Route::get('auth/getReset/{token}', 'Auth\PasswordController@getReset');
Route::get('auth/register', [
    'uses'       => 'Auth\AuthController@getRegister' /*,
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator']*/
]);
Route::post('auth/postEmail',
    ['as' => 'auth.password.email', 'uses' => 'Auth\PasswordController@postEmail']);
Route::post('auth/postReset',
    ['as' => 'auth.password.reset', 'uses' => 'Auth\PasswordController@postReset']);
Route::post('auth/login',
    ['as' => 'auth.login', 'uses' => 'Auth\AuthController@postLogin']);
Route::post('auth/register',
    ['as' => 'auth.register', 'uses' => 'Auth\AuthController@postRegister']);

// Dashboard
Route::get('/dashboard', 'DashboardController@index');

// Plans
Route::get('plan/build', [
    'middleware' => 'roles',
    'uses'       => 'PlansController@build',
    'roles'      => ['root', 'administrator']
]);
Route::get('plan/review', 'PlansController@review');
Route::get('plan/response/{id}',
    ['as' => 'plan.response', 'uses' => 'PlansController@response']);
Route::get('plan/viewAll', 'PlansController@viewAll');


Route::post('plan/saveUserResponse',
    ['as' => 'plan.save.user.response', 'uses' => 'PlansController@saveUserResponse']);
Route::post('plan/save', 'PlansController@save');
Route::resource('plan', 'PlansController');

// Tickets
Route::get('ticket/build', [
    'middleware' => 'roles',
    'uses'       => 'TicketsController@build',
    'roles'      => ['root', 'administrator']
]);
Route::get('ticket/respond', 'TicketsController@respond');
Route::resource('ticket', 'TicketsController');

// Testers
Route::get('tester/build', [
    'middleware' => 'roles',
    'uses'       => 'TesterController@build',
    'roles'      => ['root', 'administrator']
]);
Route::resource('browser-tester', 'TesterController');