<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth.manager', function()
{
	if (Auth::manager()->guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		return Redirect::guest('/manage/login');
	}
});



Route::filter('auth.manager.basic', function()
{
	return Auth::manager()->basic();
});


Route::filter('role.edit_managers', function()
{
	if(!in_array(Auth::manager()->get()->role,[21])){
		return View::make('error_unauthorized');
	}
});

Route::filter('role.event_categories', function()
{
	if(!in_array(Auth::manager()->get()->role,[21])){
		return View::make('error_unauthorized');
	}
});




Route::filter('role.events', function()
{
	if(!in_array(Auth::manager()->get()->role,[1,3,5,8,21])){
		return View::make('error_unauthorized');
	}
});


Route::filter('role.event_edit', function()
{
	if(!in_array(Auth::manager()->get()->role,[1,2,3,5,6,8,21])){
		return View::make('error_unauthorized');
	}
});


Route::filter('role.homepage', function()
{
	if(!in_array(Auth::manager()->get()->role,[1,3,5,21])){
		return View::make('error_unauthorized');
	}
});

Route::filter('role.colleges', function()
{
	if(!in_array(Auth::manager()->get()->role,[1,3,8,10,11,21])){
		return View::make('error_unauthorized');
	}
});


Route::filter('role.student_registrations', function()
{
	if(!in_array(Auth::manager()->get()->role,[1,3,4,5,6,7,8,21])){
		return View::make('error_unauthorized');
	}
});



Route::filter('role.hospitality', function()
{
	if(!in_array(Auth::manager()->get()->role,[3,4,5,21])){
		return View::make('error_unauthorized');
	}
});



Route::filter('role.hospitality', function()
{
	if(!in_array(Auth::manager()->get()->role,[3,4,5,21])){
		return View::make('error_unauthorized');
	}
});



Route::filter('role.software', function()
{
	if(!in_array(Auth::manager()->get()->role,[9,10,11,21])){
		return View::make('error_unauthorized');
	}
});


//For Results, Block Events, Verify/Add Colleges
Route::filter('role.software_level1', function()
{
	if(!in_array(Auth::manager()->get()->role,[10,11,21])){
		return View::make('error_unauthorized');
	}
});


//For Statistics, Admin Features
Route::filter('role.software_level2', function()
{
	if(!in_array(Auth::manager()->get()->role,[11,21])){
		return View::make('error_unauthorized');
	}
});


Route::filter('role.hospitality_manager', function()
{
	if(!in_array(Auth::manager()->get()->role,[12,21])){
		return View::make('error_unauthorized');
	}
});




Route::filter('auth.user', function()
{
	if (Auth::user()->guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		return Redirect::away(Config::get('app.homepage'));
	}
});


Route::filter('auth.user.basic', function()
{
	return Auth::user()->basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() !== Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
