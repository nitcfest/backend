<?php


/*
NOTE TO DEVELOPERS

The setCallback(Input::get('callback')) is used on responses to allow for JSONP to work.
This is required if the website front end is being developed locally while reading from the API on the server.

*/

class ApiController extends BaseController {


	public function index()
	{
		$updates = Update::orderBy('created_at','desc')->where('displayed','=',true)->lists('text');

		return Response::json(array(
			'status'=>'active',
			'updates' => $updates,
			))->setCallback(Input::get('callback'));
	}


	public function events(){
		//Get base category
		$categories = EventCategories::where('parent_id','=',0)->get();

		$categories->map(function($category){
			//get childrens
			$sub = EventCategories::where('parent_id', '=', $category->id)->get();

			if($sub->count()>0)
				$category->sub_categories = $sub;

			$sub->map(function($sub_cat){
				$events = Events::where('category_id','=',$sub_cat->id)->whereValidated(true)->get(['event_code', 'name', 'tags', 'prizes', 'short_description', 'team_min', 'team_max']);

				if($events->count()>0)
					$sub_cat->events = $events;

				return $sub_cat;
			});

			$events = Events::where('category_id','=',$category->id)->whereValidated(true)->get(['event_code', 'name', 'tags', 'prizes', 'short_description', 'team_min', 'team_max']);

			if($events->count()>0)
				$category->events = $events;


			return $category;

		});

		return Response::json($categories)->setCallback(Input::get('callback'));
	}


	public function event($code){

		$event = Events::where('event_code','=',$code)->get();

		if($event->count() == 0)
			return Response::json(['response'=>'error','reason'=>'no_event']);

		$event = $event->first();

		$long_description = $event->long_description;

		$sections = preg_split('/\|\|sec\|\|/m', $long_description, -1, PREG_SPLIT_NO_EMPTY);
		$sections_array = array();
		foreach ($sections as $section) {
			$parts = preg_split('/\|\|ttl\|\|/m', $section, 2, PREG_SPLIT_NO_EMPTY);
			array_push($sections_array, array('title'=>$parts[0],'text'=>$parts[1]));
		}

		$contacts_raw = $event->contacts;
		$contacts = preg_split('/\|\|con\|\|/m', $contacts_raw, -1);
		$contacts_array = array();
		foreach ($contacts as $contact) {
			$parts = preg_split('/\|\|@\|\|/m', $contact, 4, PREG_SPLIT_NO_EMPTY);

			if(trim($parts[0]) == '')
				continue;

			array_push($contacts_array,array(
					'name' => trim($parts[0]),
					'phone' => trim($parts[1]),
					'email' => trim($parts[2]),
					'facebook' => trim($parts[3]),
					));
		}


		$event->prizes = str_replace("\n", '<br>', $event->prizes);

								
		$return_details = array(
			'response' => 'success',
			'event_code' => $event->event_code,
			'category_id' => $event->category_id,
			'name' => $event->name,
			'tags' => $event->tags,
			'event_email' => $event->event_email,
			'prizes' => $event->prizes,
			'short_description' => $event->short_description,
			'team_min' => $event->team_min,
			'team_max' => $event->team_max,
			'validated' => $event->validated,
			'updated_at' => $event->updated_at 	,
			'sections' => $sections_array,
			'contacts' => $contacts_array,
			);


		return Response::json($return_details)->setCallback(Input::get('callback'));
	}


	public function user(){
		if(Auth::user()->check()){
			$user =  Auth::user()->get();


			// return Registration::whereId($id)->with('college')->get(['email','name','phone','runtime_id','college_id']);
	
			$response = array(
				'status' => 'logged_in',
				'user' => array(
					'name' => $user->name,
					'email' => $user->email,
					'phone' => $user->phone,
					// 'college' => '',

					'events' => array(
						array(
							'name' => 'Jump High',
							'team_id' => 'JHI105',
							'team' => 'ASD, PQW, ZLS',
							),
						)
					)
				);

			return Response::json($response)->setCallback(Input::get('callback'));
		}else{
			return Response::json(['status'=>'not_logged_in'])->setCallback(Input::get('callback'));
		}
	}

	public function userPostLogin(){
		$email = Input::get('email');
		$password = Input::get('password');

		if(Auth::user()->attempt(array('email' => $email, 'password' => $password)))
		{
			$user =  Auth::user()->get();
			$response = array(
				'result' => 'success',
				'user' => array(
					'name' => $user->name,
					'email' => $user->email,
					'phone' => $user->phone,
					// 'college' => '',

					'events' => array(
						array(
							'name' => 'Jump High',
							'team_id' => 'JHI105',
							'team' => 'ASD, PQW, ZLS',
							)
						)
					)
				);


			return Response::json($response)->setCallback(Input::get('callback'));
		}

		return Response::json(['result' => 'fail', 'reason' => 'invalid_credentials'])->setCallback(Input::get('callback'));
	}

	public function userLogout(){
		Auth::user()->logout();

		return Response::json(['result'=>'success'])->setCallback(Input::get('callback'));
	}


	public function userFbLogin(){

		$code = Input::get('code');
		$fb = OAuth::consumer('Facebook');

		//If code is not empty, try to get details.
		if (!empty($code)) {

			try {
				$token = $fb->requestAccessToken($code);		
			} catch (Exception $e) {

				if (Request::ajax())
					return Response::json(['result'=>'fail','reason'=>'fb_exception']);
				
				return Redirect::intended(Config::get('app.homepage'));	
			}

			$result = json_decode( $fb->request( '/me' ), true);

			//Make sure we have the intended results.
			if(is_array($result) && array_key_exists('id', $result)){
				$user = Registration::where('fb_uid', '=', $result['id'])->get();

				if($user->count() == 0){
					//Check if email has been registered.
					if(array_key_exists('email', $result)){
						$user = Registration::where('email','=',$result['email'])->get();

						if($user->count() > 0){
							$user = $user->first();
							$user->fb_uid = $result['id'];

							if($user->name == '')
								$user->name = $result['first_name'].' '.$result['last_name'];

							$user->save();

							Auth::user()->login($user);

							if(Request::ajax())
								return Response::json(['result'=>'success']);
							return Redirect::intended(Config::get('app.homepage'));
						}
					}

					//In case user has not registered before OR if FB doesn't provide email
					$user = new Registration;
					$user->fb_uid = $result['id'];
					$user->email = $result['email'];
					$user->name = $result['first_name'].' '.$result['last_name'];
					$user->save();

					Auth::user()->login($user);

					if(Request::ajax())
						return Response::json(['result'=>'success']);

					return Redirect::intended(Config::get('app.homepage'));
				}else{
					//User has already logged in with FB before.
					Auth::user()->login($user->first());


					if(Request::ajax())
						return Response::json(['result'=>'success']);

					return Redirect::intended(Config::get('app.homepage'));	
				}
			}else{
				//Some error occured and the result is not retrieved.
				if(Request::ajax())
					return Response::json(['result'=>'fail','reason'=>'no_result']);

				return Redirect::intended(Config::get('app.homepage'));				
			}
		}else{
			$url = $fb->getAuthorizationUri();


			if(Request::ajax())
				return Response::json(['result'=>'fail','reason'=>'requires_redirect','url'=>(string)$url]);

			return Redirect::to( (string)$url );
		}

	}


	public function userSignup(){
		$rules = array(
			'name' => 'required|min:3',
			'email' => 'required|email|unique:registrations',
			'password' => 'required|confirmed|min:4',
			'college' => 'required|numeric|exists:colleges,id',
			'hospitality_type' => 'required|in:0,1,2',
			'phone' => 'max:15'
			);

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			$print = '';
		    $messages = $validator->messages();

		    foreach ($messages->all() as $message)
		    {
		    	$print.= $message."<br>";   
		    }

		    return Response::json(['result'=>'fail', 'error_messages'=>$print ])->setCallback(Input::get('callback'));
		}

		$registration = new Registration;
		$registration->name = Input::get('name');
		$registration->email = Input::get('email');
		$registration->password = Hash::make(Input::get('password'));

		$registration->college_id = Input::get('college');
		$registration->phone = Input::get('phone');
		$registration->hospitality_type = Input::get('hospitality_type');

		$registration->save();


		if(Auth::user()->attempt(array('email' => Input::get('email'), 'password' => Input::get('password'))))
		{
			$user =  Auth::user()->get();

			$response = array(
				'result' => 'success',
				'user' => array(
					'name' => $user->name,
					'email' => $user->email,
					'phone' => $user->phone,
					// 'college' => '',

					'events' => array(
						array(
							'name' => 'Jump High',
							'team_id' => 'JHI105',
							'team' => 'ASD, PQW, ZLS',
							)
						)
					)
				);


			return Response::json($response)->setCallback(Input::get('callback'));
		}

		return Response::json(['result'=>'fail', 'error_messages'=>'There was an error during registration. Please try again.' ])->setCallback(Input::get('callback'));
	}



	public function collegeSearch(){
		$query = Input::get('q', '');
		$page = Input::get('page', 1);
		
		$page--;

		if(strlen($query) >= 2){
			$colleges = College::where('name','LIKE','%'.$query.'%')->whereValidated(true);
			$total_count = $colleges->count();
			$colleges = $colleges->skip($page*10)->take(10)->get(['id','name']);
		}else{
			$total_count = 0;
			$colleges = [];
			return Response::json([
				'result' => 'too_small_query'
				])->setCallback(Input::get('callback'));
		}

		return Response::json([
			'total_count' => $total_count,
			'colleges' => $colleges
			])->setCallback(Input::get('callback'));
	}



}
