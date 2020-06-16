<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');

Route::group(['middleware' => 'auth:api'], function(){

	Route::post('profile', 'API\UserController@profile');


//provider routes
	Route::get('profile/update', 'API\UserController@provider_profile')
	->middleware('Role:provider');

	Route::get('all/service', 'API\ServiceController@index')
	->middleware('Role:provider');

	Route::post('provider/service/update', 'API\ServiceController@update')
	->middleware('Role:provider');

	Route::post('provider/service/delete/{id}', 'API\ServiceController@delete_service')
	->middleware('Role:provider');

	Route::get('provider/service/all', 'API\ServiceController@show')
	->middleware('Role:provider');

	Route::get('/accept/request/{request_id}', 'API\ServiceController@accept_request')
	->middleware('Role:provider');
	Route::get('/reject/request/{request_id}', 'API\ServiceController@reject_request')
	->middleware('Role:provider');

//customer routes

	Route::get('service', 'API\ServiceController@customer_service_list')
	->middleware('Role:customer');

	Route::get('send/request/{service_id}', 'API\ServiceController@send_request')
	->middleware('Role:customer');

});
