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
	    	'uses'=> 'ApiController@events'
	    	));


	    Route::get('event/{code}', array(
	    	'uses'=> 'ApiController@event'
	    	));


	    Route::get('user', array(
	    	'uses' => 'ApiController@user'
	    	));

	    Route::post('user/login', array(
	    	'uses' => 'ApiController@userPostLogin'
	    	));

	    Route::get('user/logout', array(
	    	'uses' => 'ApiController@userLogout'
	    	));

	    Route::get('user/fb_login', array(
	    	'uses' => 'ApiController@userFbLogin'
	    	));


	}
);



Route::get('/user', array('before'=>'auth.user', function()
{
	echo 'shit';
	// return Auth::user()->get();
}));

