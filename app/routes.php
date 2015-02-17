<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


/*
Base URL:
http://www.ragam.org.in/2015/cms/


Normal API:
/api/


Manage:
/manage/

*/

Route::get('/', function()
{
	return Redirect::away(Config::get('app.homepage'));
});


Route::group(array(
	'prefix' => 'api'
	), function(){
	    Route::get('/', array(
	    	'uses'=> 'ApiController@index'
	    	));

	    Route::get('events', array(
	    	'as'=>'events', 
	    	'uses'=> 'ApiController@events'
	    	));


	    Route::get('event/{code}', array(
	    	'as'=>'event', 
	    	'uses'=> 'ApiController@event'
	    	));

	}
);



// Route::get('/admin-api', array('before'=>'auth.manager', function()
// {
// 	return Response::json(array('status'=>'active'));
// }));


// Route::get('/user', array('before'=>'auth.manager', function()
// {
// 	return Response::json(array('status'=>'active'));
// }));

