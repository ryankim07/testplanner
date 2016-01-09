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
 *
 * Auth
 *
 */
Route::get('/', 'AuthController@getLogin');
Route::get('auth/logout', ['as' => 'auth.logout', 'uses' => 'AuthController@getLogout']);
Route::get('auth/login', ['as' => 'auth.login', 'uses' => 'AuthController@getLogin']);
Route::post('auth/postLogin', ['as' => 'auth.post.login', 'uses' => 'AuthController@postLogin']);

Route::get('auth/register', [
    'as'         => 'auth.register',
    'middleware' => 'roles',
    'roles'      => ['root'],
    'uses'       => 'AuthController@getRegister'
]);

Route::post('auth/postRegister', [
    'as'         => 'auth.post.register',
    'middleware' => 'roles',
    'roles'      => ['root'],
    'uses'       => 'AuthController@postRegister'
]);

Route::get('password/getEmail', ['as' =>'password.email', 'uses' => 'PasswordController@getEmail']);
Route::post('password/postEmail', ['as' => 'password.post.email', 'uses' => 'PasswordController@postEmail']);
Route::get('password/getReset/{token}', ['as' =>'password.reset', 'uses' => 'PasswordController@getReset']);
Route::post('password/postReset', ['as' => 'password.post.reset', 'uses' => 'PasswordController@postReset']);


/**
 *
 * Users
 *
 */
Route::get('user/view/{user_id}', [
    'as'         => 'user.view',
    'middleware' => 'roles',
    'roles'      => ['root'],
    'uses'       => 'UsersController@view'
]);
Route::get('user/all', [
    'as'         => 'user.view.all',
    'middleware' => 'roles',
    'roles'      => ['root'],
    'uses'       => 'UsersController@all']);

Route::get('user/search', 'UsersController@search');
Route::post('user/search', ['as' => 'user.search', 'uses' => 'UsersController@search']);

Route::post('user/update', [
    'as'         => 'user.update',
    'middleware' => 'roles',
    'roles'      => ['root'],
    'uses'       => 'UsersController@update'
]);


/**
 *
 * Dashboard
 *
 */
Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);


/**
 *
 * Activity Stream
 *
 */
Route::get('activity', ['as' => 'activity', 'uses' => 'ActivityStreamController@index']);
Route::get('activity/view-all', ['as' => 'activity.view.all', 'uses' => 'ActivityStreamController@all']);
Route::get('activity/search', 'ActivityStreamController@search');
Route::post('activity/search', ['as' => 'activity.search', 'uses' => 'ActivityStreamController@search']);
Route::post('activity/save-comment', ['as' => 'activity.comment.save', 'uses' => 'ActivityStreamController@saveComment']);


/**
 *
 * Plans
 *
 */
Route::get('plan/view/{id}', ['as' => 'plan.view', 'uses' => 'PlansController@view']);
Route::get('plan/response/{plan_id}/{user_id}', ['as' => 'plan.view.response', 'uses' => 'PlansController@response']);
Route::get('plan/respond/{plan_id}', ['as' => 'plan.respond', 'uses' => 'PlansController@respond']);

Route::get('plan/build', [
    'as'         => 'plan.build',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'PlansController@build'
]);

Route::get('plan/review', [
    'as'         => 'plan.review',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'PlansController@review'
]);
Route::get('plan/view-all-created/{id}', [
    'as'         => 'plan.view.all.created',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'PlansController@viewAllCreated'
]);

Route::get('plan/view-all-responses', [
    'as'         => 'plan.view.all.responses',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'PlansController@viewAllResponses']);

Route::get('plan/view-all-assigned', ['as' => 'plan.view.all.assigned', 'uses' => 'PlansController@viewAllAssigned']);

Route::post('plan/save', [
    'as'         => 'plan.save',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'PlansController@save']);

Route::get('plan/search', 'PlansController@search');
Route::post('plan/search', ['as' => 'plan.search', 'uses' => 'PlansController@search']);
Route::post('plan/save-user-response', ['as' => 'plan.save.user.response', 'uses' => 'PlansController@saveUserResponse']);

Route::patch('plan/update-plan-details/{id}', ['as' => 'plan.update.details', 'uses' => 'PlansController@updatePlansDetails']);
Route::put('plan/update-plan-details/{id}', ['as' => 'plan.update.details', 'uses' => 'PlansController@updatePlansDetails']);
Route::resource('plan', 'PlansController');


/**
 *
 * Tickets
 *
 */
Route::get('ticket/build', [
    'as'         => 'ticket.build',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'TicketsController@build'
]);
Route::get('ticket/response', ['as' => 'plan.response', 'uses' => 'TicketsController@response']);

Route::post('ticket/remove', [
    'as'   => 'ticket.remove.ajax',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses' => 'TicketsController@removeAjax'
]);

Route::resource('ticket', 'TicketsController');


/**
 *
 * Testers
 *
 */
Route::get('tester/build', [
    'as'         => 'tester.build',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'TestersController@build'
]);
Route::resource('tester', 'TestersController');