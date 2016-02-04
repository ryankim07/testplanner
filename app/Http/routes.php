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
 * System Configuration
 *
 */
Route::get('system/all', [
    'as'         => 'system.view.all',
    'middleware' => 'roles',
    'roles'      => ['root'],
    'uses'       => 'SystemController@index']);
Route::post('system/update', [
    'as'         => 'system.update',
    'middleware' => 'roles',
    'roles'      => ['root'],
    'uses'       => 'SystemController@update']);


/**
 *
 * Auth
 *
 */
Route::get('/', 'AuthController@getLogin');
Route::get('auth/logout', ['as' => 'auth.logout', 'uses' => 'AuthController@getLogout']);
Route::get('auth/login', ['as' => 'auth.login', 'uses' => 'AuthController@getLogin']);
Route::post('auth/postLogin', ['as' => 'auth.post.login', 'uses' => 'AuthController@postLogin']);
Route::get('password/getEmail', ['as' =>'password.email', 'uses' => 'PasswordController@getEmail']);
Route::post('password/postEmail', ['as' => 'password.post.email', 'uses' => 'PasswordController@postEmail']);
Route::get('password/getReset/{token}', ['as' =>'password.reset', 'uses' => 'PasswordController@getReset']);
Route::post('password/postReset', ['as' => 'password.post.reset', 'uses' => 'PasswordController@postReset']);

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


/**
 *
 * Users
 *
 */
Route::get('user/view', [
    'as'         => 'user.view',
    'middleware' => 'roles',
    'roles'      => ['root'],
    'uses'       => 'UsersController@view'
]);
Route::get('user/all', [
    'as'         => 'user.view.all',
    'middleware' => 'roles',
    'roles'      => ['root'],
    'uses'       => 'UsersController@index']);

Route::post('user/update', [
    'as'         => 'user.update',
    'middleware' => 'roles',
    'roles'      => ['root'],
    'uses'       => 'UsersController@update'
]);
Route::get('user/search', 'UsersController@search');
Route::post('user/search', ['as' => 'user.search', 'uses' => 'UsersController@search']);


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
Route::get('activity/view-all', ['as' => 'activity.view.all', 'uses' => 'ActivityStreamController@index']);
Route::get('activity/search', 'ActivityStreamController@search');
Route::post('activity/search', ['as' => 'activity.search', 'uses' => 'ActivityStreamController@search']);
Route::post('activity/save-comment', ['as' => 'activity.comment.save', 'uses' => 'ActivityStreamController@save']);


/**
 *
 * Plans
 *
 */
Route::get('plan/view/{id}', ['as' => 'plan.view', 'uses' => 'PlansController@view']);
Route::get('plan/response/{plan_id}', ['as' => 'plan.view.response', 'uses' => 'PlansController@response']);
Route::get('plan/respond/{plan_id}', ['as' => 'plan.respond', 'uses' => 'PlansController@respond']);
Route::get('plan/view-all-assigned', ['as' => 'plan.view.all.assigned', 'uses' => 'PlansController@viewAllAssigned']);

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
Route::get('plan/view-all-created', [
    'as'         => 'plan.view.all.created',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'PlansController@viewAllCreated'
]);
Route::post('plan/view-all-created', [
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

Route::post('plan/save', [
    'as'         => 'plan.save',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'PlansController@save']);

Route::patch('plan/update-built-plan/{id}', [
    'as'         => 'plan.built.update',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'PlansController@updateBuiltPlan']);
Route::put('plan/update-built-plan/{id}', [
    'as'         => 'plan.built.update',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'PlansController@updateBuiltPlan']);

Route::get('plan/search', 'PlansController@search');
Route::post('plan/search', ['as' => 'plan.search', 'uses' => 'PlansController@search']);
Route::resource('plan', 'PlansController', ['except' => ['create', 'show', 'destroy']]);


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

Route::get('ticket/edit', [
    'as'         => 'ticket.edit',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'TicketsController@edit']);

Route::post('ticket/remove', [
    'as'         => 'ticket.remove.ajax',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'TicketsController@removeTicketAjax'
]);

Route::post('ticket/save-ticket-response', ['as' => 'ticket.save.response', 'uses' => 'TicketsController@save']);
//Route::resource('ticket', 'TicketsController', ['except' => ['index', 'create', 'edit', 'show', 'destroy']]);
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
Route::get('tester/edit', [
    'as'         => 'tester.edit',
    'middleware' => 'roles',
    'roles'      => ['root', 'administrator'],
    'uses'       => 'TestersController@edit']);
Route::resource('tester', 'TestersController', ['except' => ['index', 'create', 'edit', 'show', 'destroy']]);

/*Event::listen('illuminate.query',function($query){
    var_dump($query);
});*/