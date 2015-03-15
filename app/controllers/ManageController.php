<?php


class ManageController extends BaseController {

	public function login(){
		if (Auth::manager()->check()) {
		    return Redirect::route('manager_dashboard');
		} else {
		    return View::make('login');
		}
	}

	public function postLogin(){
		$email = Input::get('email');
		$password = Input::get('password');

		if (Auth::manager()->attempt(array('email' => $email, 'password' => $password, 'validated' => true)))
		{
		    return Redirect::intended(route('manager_dashboard'));
		}else{
			Session::flash('error', 'Invalid email or password.');
			return Redirect::route('manager_login')->withInput();
		}
	}

	public function signup(){

		$event_categories = EventCategories::get(['id','parent_id','name']);

		return View::make('signup', array('event_categories'=>$event_categories));
	}

	public function postSignup(){
		$rules = array(
			'name' => 'required|min:3',
			'email' => 'required|email|unique:managers',
			'roll_no' => 'required|min:3',
			'password' => 'required|confirmed|min:4',
			'role' => 'required|in:2,8', //Limit signup requests to Event Managers and Proofreaders.
			);

		$validator = Validator::make(Input::all(), $rules);

		// TODO
		// Un-hardcode MIN and MAX

		$validator->sometimes('event_name', 'required|min:3', function($input)
		{
		    return $input->role == 2;
		});

		$validator->sometimes('event_code', 'required|alpha_num|min:3|max:3', function($input)
		{
		    return $input->role == 2;
		});

		$validator->sometimes('category_id', 'required|numeric|exists:event_categories,id', function($input)
		{
		    return $input->role == 2;
		});



		if ($validator->fails())
		{
			$print = '';
		    $messages = $validator->messages();

		    foreach ($messages->all() as $message)
		    {
		    	$print.= $message."<br>";   
		    }


			Session::flash('errors', $print);
			return Redirect::route('manager_signup')->withInput();			
		}

		$manager = new Manager;
		$manager->name = Input::get('name');
		$manager->email = Input::get('email');

		$manager->roll_no = Input::get('roll_no');
		$manager->password = Hash::make(Input::get('password'));
		$manager->role = Input::get('role');

		$manager->signup_data = Input::get('event_name').'||@||'.Input::get('category_id');

		$manager->event_code = strtoupper(Input::get('event_code', NULL));
		$manager->validated = false;

		$manager->save();


		Session::flash('notice', 'You may log in after the admin validates your account.');
		return Redirect::route('manager_login');
	}



	public function logout(){
		Auth::manager()->logout();

		Session::flash('notice', 'You have logged out.');

		return Redirect::route('manager_login');
	}

	public function index()
	{
		$events_count = Events::where('validated','=',true)->count();
		$managers_count = Manager::where('validated','=',true)->count();

		$teams_count = Team::count();
		$registrations_count = Registration::count();

		$manager = Auth::manager()->get();

		if($manager->role == 2 && $manager->event_code!=''){
			//If the user is an event manager, make sure an event with such a code is available. Else, create it.
			//TODO - Reorganize
			$event = Events::where('event_code','=',$manager->event_code)->get();

			if($event->count() == 0){
				//There is no event with the event code of the logged in user.

				$new_event = new Events;
				$new_event->event_code = $manager->event_code;

				//Retrieve data that was stored during registration/signup.
				$parts = preg_split('/\|\|@\|\|/m', $manager->signup_data, 2, PREG_SPLIT_NO_EMPTY);
				if(count($parts)==2){
					$new_event->name = $parts[0];

					if(is_numeric($parts[1]))
						$new_event->category_id = $parts[1];
					else
						$new_event->category_id = 1;
				}else{
					$new_event->category_id = 1;
				}

				$new_event->contacts = ' ||@|| ||@|| ||@|| ||con|| ||@|| ||@|| ||@|| ||con|| ||@|| ||@|| ||@|| ';
				$new_event->long_description = 'Introduction||ttl|| ';
				$new_event->prizes = "First Prize:\r\nSecond Prize:\r\nThird Prize:";
				$new_event->validated = false;

				$new_event->save();
			}
		}



		return View::make('dashboard', array(
			'events_count'=>$events_count, 
			'managers_count'=>$managers_count, 
			'event_code'=>$manager->event_code,
			'teams_count' => $teams_count,
			'registrations_count' => $registrations_count,
			));
	}

	public function managers()
	{
		$managers = Manager::orderBy('created_at','desc')->get(['id','name','email','role','roll_no','event_code','validated']);

		$managers->map(function($manager){

			if($manager->validated == 0)
				$manager->status = 'Not Validated';
			else
				$manager->status = 'Active';

			if($manager->event_code == '')
				$manager->event_code = '-';

			$manager->type = $this->getRoleName($manager->role);

			return $manager;

		});

		return View::make('managers', array('managers'=>$managers));
	}

	public function managersNew(){
		$rules = array(
			'email' => 'required|email|unique:managers',
			'password' => 'required|min:4',
			'role' => 'numeric',
			);
		$validator = Validator::make(Input::all(), $rules);

		$validator->sometimes('event_code', 'required|alpha_num|min:3|max:3', function($input)
		{
		    return $input->role == 2;
		});


		if ($validator->fails())
		{
			$print = '';
		    $messages = $validator->messages();

		    foreach ($messages->all() as $message)
		    {
		    	$print.= $message."<br>";   
		    }


			Session::flash('error', $print);
			return Redirect::route('manager_managers');
		}

		$manager = new Manager;

		$manager->email = Input::get('email');
		$manager->password = Hash::make(Input::get('password'));
		$manager->role = Input::get('role');
		$manager->event_code = strtoupper(Input::get('event_code',NULL));
		$manager->validated = true;
		$manager->save();

		Session::flash('success', 'Manager added successfully.');
		return Redirect::route('manager_managers');
	}


	public function managersChangeStatus(){
		$id = Input::get('id');
		$to = Input::get('to');

		$manager = Manager::whereId($id)->first();

		if($to == 'validate')
			$manager->validated = true;
		else if($to == 'invalidate')
			$manager->validated = false;

		$manager->save();

		Session::flash('success', 'Manager status updated.');
		return Redirect::route('manager_managers');
	}


	public function eventCategories()
	{
		$event_categories = EventCategories::with('events')->get();


		$event_categories->map(function($category){

			if($category->parent_id == 0)
				$category->type = 'Main';
			else{
				$parent_name = EventCategories::where('id','=',$category->parent_id)->first()->name;
				$category->type = 'Sub:'.$parent_name;
			}

			$category->events = Events::where('category_id','=',$category->id)->count();

			return $category;

		});

		return View::make('event_categories', array('event_categories'=>$event_categories));
	}

	public function eventCategoriesNew(){
		$name = Input::get('name');
		$parent_id = Input::get('parent_id');

		if($name!='' && is_numeric($parent_id)){
			$new_category = new EventCategories;

			$new_category->name = $name;
			$new_category->parent_id = $parent_id;
			$new_category->save();

			Session::flash('success', 'Category added.');
			return Redirect::route('manager_event_categories');
		}

		Session::flash('error', 'Could not add category.');
		return Redirect::route('manager_event_categories');
	}

	public function eventCategoriesDelete($id = NULL){

		$count_events = Events::where('category_id','=',$id)->count();

		if($count_events == 0){
			EventCategories::destroy($id);

			Session::flash('success', 'Category deleted.');
			return Redirect::route('manager_event_categories');
		}else{


			Session::flash('error', 'Could not delete category.');
			return Redirect::route('manager_event_categories');
		}
	}


	public function events()
	{
		$events = Events::with('category')->orderBy('created_at','desc')->get();

		// return $events;
		$events->map(function($event){

			if($event->validated == 0)
				$event->status = 'Not Validated';
			else
				$event->status = 'Validated';

			return $event;

		});

		return View::make('events', array('events'=>$events));
	}

	public function eventsNew(){
		$event_categories = EventCategories::get(['id','parent_id','name']);

		$event = new stdClass();
		$event->event_code = 'XYZ';
		$event->name = '';
		$event->category_id = 0;
		$event->short_description = '';
		$event->tags = '';
		$event->team_min = 1;
		$event->team_max = 1;
		$event->prizes = "First Prize:\nSecond Prize:\nThird Prize:";
		$event->event_email = '';

		$data = new stdClass();
		$data->contacts = array(
			[
				'name'=>'',
				'phone' =>'',
				'email' =>'',
				'facebook' =>''
			],
			[
				'name'=>'',
				'phone' =>'',
				'email' =>'',
				'facebook' =>''
			],
			[
				'name'=>'',
				'phone' =>'',
				'email' =>'',
				'facebook' =>''
			],
			);

		$data->sections = array(
			[
				'title'=>'Introduction',
				'text' =>'Enter the introduction here.'
			]);

		return View::make('events_edit',array(
			'event' => $event,  
			'data' => $data,
			'event_categories' => $event_categories,
			'page_type' => 'new'
			));
	}

	public function eventsChangeStatus(){
		$id = Input::get('id');
		$to = Input::get('to');

		$event = Events::whereId($id)->first();

		if($to == 'validate')
			$event->validated = true;
		else if($to == 'invalidate')
			$event->validated = false;

		$event->save();

		Session::flash('success', 'Event status updated.');
		return Redirect::route('manager_events');

	}

	public function eventsEdit($id = NULL){

		$event = Events::whereId($id)->first();

		$manager = Auth::manager()->get();

		if($manager->role == 2 && $event->event_code!=$manager->event_code){
			//Make sure event managers edit only their events.
			return View::make('error_unauthorized');
		}

		$event_categories = EventCategories::get(['id','parent_id','name']);

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
			array_push($contacts_array,array(
					'name' => $parts[0],
					'phone' => $parts[1],
					'email' => $parts[2],
					'facebook' => $parts[3],
					));
		}
								

		//Process data from rows.
		$data = new stdClass();
		$data->contacts = $contacts_array;
		$data->sections = $sections_array;

		return View::make('events_edit',array(
			'event' => $event,
			'data' => $data,
			'event_categories' => $event_categories,
			'page_type' => 'edit',
			'id' => $id,
			));
	}

	public function eventsSave(){
		$input = Input::all();

		if($input['current_id']=='new'){
			$event = new Events;
		} else {
			$event = Events::whereId($input['current_id'])->get();

			if($event->count()==0) //Unknown ID?
				return Redirect::route('manager_events');		

			$event = $event->first();	
		}

		$contacts = '';		
		for($i=0;$i<count($input['manager_name']);$i++){			

			if($i!=0)
				$contacts .= '||con||';

			$contacts.= ($input['manager_name'][$i]==''?' ':$input['manager_name'][$i]) .'||@||'.
						($input['manager_phone'][$i]==''?' ':$input['manager_phone'][$i]) .'||@||'.
						($input['manager_email'][$i]==''?' ':$input['manager_email'][$i]) .'||@||'.
						($input['manager_facebook'][$i]==''?' ':$input['manager_facebook'][$i]);
		}


		$long_description = '';
		for($i=0;$i<count($input['section_title']);$i++){			

			if($i!=0)
				$long_description .= '||sec||';

			$long_description .= $input['section_title'][$i] . '||ttl||' . $input['section_description'][$i];
		}

		if(strlen($input['event_code'])==3)
			$event->event_code = strtoupper($input['event_code']);

		if(array_key_exists('short_description', $input)){
			$event->short_description = $input['short_description'];
		}

		$event->name = $input['name'];
		$event->category_id = $input['category_id'];
		$event->long_description = $long_description;
		$event->tags = $input['tags'];
		$event->team_min = $input['team_min'];
		$event->team_max = $input['team_max'];
		$event->prizes = $input['prizes'];
		$event->event_email = $input['event_email'];
		$event->contacts = $contacts;

		$event->save();

		if($input['current_id']=='new')
			Session::flash('success', 'New event created.');
		else
			Session::flash('success', 'Event details updated.');
		
		return Redirect::route('action_edit_event', $event->id);

	}

	public function eventsUploadImage(){
		//Function to upload images to public/images/ folder.
		$upload_path = public_path().'/images/';
		$event_code = Input::get('event_code','ZZZ');

		if (Input::hasFile('image'))
		{
			$file = Input::file('image');
			$size = $file->getSize();
			$filename = $file->getClientOriginalName();

			if ($file->isValid())
			{
				$extension = $file->getClientOriginalExtension();
				$mime = $file->getMimeType();
				$size = $file->getSize();

				if(!in_array($extension, ['gif','jpg','png','jpeg']) || !in_array($mime, ['image/gif','image/jpeg','image/pjpeg','image/png'])){
					//Not an image?
					return Response::json([
						'files'=>[
								[
									'name'=>$filename,
									'size'=>$size,
									'error'=>'Invalid image uploaded.'
								]
							]

						]);
				}

				if($size> 5*1024*1024){ //5MB Maximum
					return Response::json([
						'files'=>[
								[
									'name'=>$filename,
									'size'=>$size,
									'error'=>'File too large(>5MB). Try a smaller image.'
								]
							]

						]);
				}

				list($width, $height) = getimagesize($file->getRealPath());

				if($width>900){
					return Response::json([
						'files'=>[
								[
									'name'=>$filename,
									'size'=>$size,
									'error'=>'Try a smaller image. Maximum width is 900 pixels.'
								]
							]

						]);
				}				


				$new_filename = $event_code.'_1';
				if(file_exists($upload_path.$new_filename.".".$extension)){
					$i=1;
					while(file_exists($upload_path.$new_filename.".".$extension)){
						//Files names are like ABC.jpg, ABC_2.jpg etc.
						$i++;						
						$new_filename = $event_code.'_'.$i;
					}
				}

				//Now we have a useable filename for the newly uploaded image.
				$file->move($upload_path, $new_filename.".".$extension);
				$public_url = Config::get('app.url').'/images/'.$new_filename.".".$extension;
				return Response::json([
					'files'=>[
							[
								'name'=>$filename,
								'size'=>$size,
								'thumbnailUrl'=> $public_url,
								'url'=> $public_url,
							]
						]

					]);
			}
		}



		return Response::json([
			'files'=>[
					[
						'error'=>'Invalid file.'
					]
				]

			]);

	}


	public function eventsRedirectToEdit(){
		$manager_event = Auth::manager()->get()->event_code;

		$event = Events::where('event_code','=',$manager_event)->get();

		if($event->count()>0)
			return Redirect::route('action_edit_event', $event->first()->id);
		else
			return Redirect::route('manager_dashboard');
	}


	public function editHomepage(){

		$updates = Update::orderBy('created_at','desc')->get();

		$updates->map(function($update){
			$update->status = 'a';

			if($update->displayed == 0)
				$update->status = 'Not Shown';
			else
				$update->status = 'Shown';

			return $update;
		});

		return View::make('edit_homepage', array('updates'=>$updates));
	}


	public function editHomepageStatus(){
		$id = Input::get('id');
		$to = Input::get('to');

		$update = Update::whereId($id)->first();

		if($to == 'show')
			$update->displayed = true;
		else if($to == 'hide')
			$update->displayed = false;

		$update->save();

		Session::flash('success', 'Status updated.');
		return Redirect::route('manager_edit_homepage');
	}

	public function editHomepageDeleteUpdate(){
		$id = Input::get('id');

		$update = Update::whereId($id);

		if($update->count()>0)
			$update->first()->delete();
		else{
			Session::flash('error', 'Could not delete update.');
			return Redirect::route('manager_edit_homepage');			
		}


		Session::flash('success', 'Update deleted.');
		return Redirect::route('manager_edit_homepage');
	}


	public function editHomepageAddUpdate(){

		$text = Input::get('text');

		if($text!=''){
			$update = new Update;
			$update->text = $text;
			$update->displayed = true;	
			$update->save();

			Session::flash('success', 'Update added..');
			return Redirect::route('manager_edit_homepage');
		}

		Session::flash('error', 'Update cannot be empty!');
		return Redirect::route('manager_edit_homepage');

	}


	public function verifyColleges(){
		$colleges = College::orderBy('created_at','desc')->whereValidated(0)->get();

		return View::make('verify_colleges', array('colleges'=>$colleges));
	}

	public function verifyCollegesStatus(){
		$id = Input::get('id');
		$to = Input::get('to');

		$name = Input::get('name');

		$college = College::whereId($id)->whereValidated(0)->get();

		if($college->count() == 0){
			Session::flash('error', 'Already updated.');
			return Redirect::route('manager_verify_colleges');
		}

		$college = $college->first();

		if($to == 'validate'){

			if($name == ''){
				Session::flash('error', 'Invalid college name.');
				return Redirect::route('manager_verify_colleges');
			}

			$college->name = $name;			
			$college->validated = 1;
			Session::flash('success', 'College validated. Users can now register with this college.');
		}else if($to == 'block'){
			$college->validated = -1;
			Session::flash('success', 'College blocked. It cannot be added again by users.');
		}

		$college->save();

		return Redirect::route('manager_verify_colleges');
	}


	public function studentRegistrations(){
		$registrations = Registration::orderBy('id','asc')->get();

		return View::make('student_registrations', array('registrations'=>$registrations));
	}


	public function eventRegistrations(){
		$teams = Team::with('event','team_members')->orderBy('event_code','asc')->orderBy('team_code','asc')->get();

		return View::make('event_registrations', array('registrations'=>$teams));
	}




	public function testApi(){
		return View::make('test_api');
	}


	private function getRoleName($role){
		switch ($role) {
			//DO NOT CHANGE ORDERS/NUMBERS FOR EXISTING ROLES
			//THESE ARE HARD CODED IN SOME PLACES.
			//IF YOU NEED MORE ROLES, CREATE THEM.


			// [1,2,3,4,5,6,7,8,21]

			case 1:
				return 'Website Admin'; //Edit details of event, Edit homepage, add news.
				break;

			case 2:
				return 'Event Manager'; //Edit details of an event, Print List
				break;

			case 3:
				return 'Registration'; //Registration, Hospitality, Results, Add Notes., Add news.
				break;

			case 4:
				return 'Hospitality'; //Hospitality, Add Notes.
				break;

			case 5:
				return 'Program Committee'; //Print List, Register for Event, Add Results, Add news.
				break;

			case 6:
				return 'Results Entry'; //Results
				break;

			case 7:
				return 'Printing Only'; //Print List, Results etc.
				break;

			case 8:
				return 'Proofreader'; //Edit details of an event, Print List
				break;

			case 21:
				return 'Super Admin'; //All features
				break;
			

			default:
				return 'Unknown'; //Unknown number in role, shouldn't occur.
				break;
		}
	}

	public function errorUnauthorized()
	{
		return View::make('error_unauthorized');
	}
}