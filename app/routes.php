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


//Login, logout features of manage.
Route::group(array(
	'prefix' => 'manage',
	), function(){
	
		Route::get('login', array(
			'as' => 'manager_login',
			'uses'=> 'ManageController@login',
			));

		Route::post('login', array(
			'before' => 'csrf',
			'uses'=> 'ManageController@postLogin',
			));

		Route::get('logout', array(
			'as' => 'manager_logout',
			'uses'=> 'ManageController@logout',
			));
	}
);

//Features after being logged in.
Route::group(array(
	'prefix' => 'manage',
	'before' => 'auth.manager',
	), function(){
	

	    Route::get('/', array(
	    	'as' => 'manager_dashboard',
	    	'uses'=> 'ManageController@index'
	    	));

	    Route::get('managers', array(
	    	'as' => 'manager_managers',
	    	'uses'=> 'ManageController@managers'
	    	));

	    Route::post('managers/new', array(
	    	'as' => 'action_new_manager',
	    	'before' => 'csrf',
	    	'uses'=> 'ManageController@managersNew'
	    	));

	    Route::get('events', array(
	    	'as' => 'manager_events',
	    	'uses'=> 'ManageController@events'
	    	));

	    Route::get('events/new', array(
	    	'as' => 'action_new_event',
	    	'uses'=> 'ManageController@eventsNew'
	    	));

	    Route::get('events/{id}', array(
	    	'as' => 'action_edit_event',
	    	'uses'=> 'ManageController@eventsEdit'
	    	));
	}
);



Route::get('/user', array('before'=>'auth.user', function()
{
	echo 'shit';
	// return Auth::user()->get();
}));

