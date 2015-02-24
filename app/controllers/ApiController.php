<?php

class ApiController extends BaseController {


	public function index()
	{
		$updates = Update::orderBy('created_at','desc')->where('displayed','=',true)->lists('text');

		return Response::json(array(
			'status'=>'active',
			'updates' => $updates,
			));
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
			$id =  Auth::user()->get()->id;
		}else{
			if(Request::ajax())
				return Response::json(['result'=>'fail','reason'=>'not_logged_in']);
		    
		    return Redirect::to(Config::get('app.homepage'));
		}

		return Registration::whereId($id)->with('college')->get(['email','name','phone','runtime_id','college_id']);
	}

	public function userPostLogin(){
		$email = Input::get('email');
		$password = Input::get('password');

		if(Auth::user()->attempt(array('email' => $email, 'password' => $password)))
		{
			if(Request::ajax())
				return Response::json(['result'=>'success']);
		    
		    return Redirect::intended(Config::get('app.homepage'));
		}
	}

	public function userLogout(){
		Auth::user()->logout();

		if(Request::ajax())
			return Response::json(['result'=>'success']);

		return Redirect::to(Config::get('app.homepage'));		
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



}
