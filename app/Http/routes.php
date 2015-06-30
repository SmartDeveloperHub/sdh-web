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

//Route::get('/', 'WelcomeController@index');

Route::get('/', function(){
    return View::make('panel');
});

Route::get('home', 'HomeController@index');


// Dashboard templates
Route::get('user-dashboard', function(){
    return View::make('dashboards.user');
});

Route::get('repo-dashboard', function(){
    return View::make('dashboards.repository');
});

Route::get('organization-dashboard', function(){
    return View::make('dashboards.organization');
});

Route::get('test-template', function(){
    return View::make('dashboards.test-template');
});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
