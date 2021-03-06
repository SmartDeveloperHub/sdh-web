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

Route::get('/', 'DashboardController@panel');
Route::get('/editor', 'DashboardEditorController@editor');
Route::get('dashboard/{name}/{env}', 'DashboardController@dashboard'); //Optional query param: ?<cat>=<rank>

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
