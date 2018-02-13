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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::group(['middleware' => 'auth'], function () {

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/teams', 'TeamController@index');
    Route::get('/teams/create', 'TeamController@create');
    Route::post('/teams', 'TeamController@store');
    Route::get('/teams/{team}', 'TeamController@show');
    Route::patch('/teams/{team}', 'TeamController@update');
    Route::delete('/teams/{team}', 'TeamController@destroy');

    Route::get('/users', 'UserController@index');
    Route::get('/users/create', 'UserController@create');
    Route::post('/users', 'UserController@store');
    Route::get('/users/{user}', 'UserController@show')->name('profile');
    Route::patch('/users/{user}', 'UserController@update');
    Route::delete('/users/{user}', 'UserController@destroy');

    Route::get('/blog-groups', 'BlogGroupController@index');
    Route::get('/blog-groups/create', 'BlogGroupController@create');
    Route::post('/blog-groups', 'BlogGroupController@store');
    Route::get('/blog-groups/{blogGroup}', 'BlogGroupController@show');
    Route::patch('/blog-groups/{blogGroup}', 'BlogGroupController@update');
    Route::delete('/blog-groups/{blogGroup}', 'BlogGroupController@destroy');

    Route::get('/blogs', 'BlogController@index');
    Route::get('/blogs/create', 'BlogController@create');
    Route::post('/blogs', 'BlogController@store');
    Route::get('/blogs/{blog}', 'BlogController@show');
    Route::patch('/blogs/{blog}', 'BlogController@update');
    Route::delete('/blogs/{blog}', 'BlogController@destroy');

    Route::get('/leadboxes', 'LeadboxController@index');
    Route::get('/leadboxes/create', 'LeadboxController@create');
    Route::post('/leadboxes', 'LeadboxController@store');
    Route::get('/leadboxes/{leadbox}', 'LeadboxController@show');
    Route::patch('/leadboxes/{leadbox}', 'LeadboxController@update');
    Route::delete('/leadboxes/{leadbox}', 'LeadboxController@destroy');

});







