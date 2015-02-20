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
			return View::make('login');
		}
	}

	public function logout(){
		Auth::manager()->logout();

		Session::flash('notice', 'You have logged out.');

		return Redirect::route('manager_login');
	}

	public function index()
	{
		return View::make('dashboard');
	}

	public function managers()
	{
		$managers = Manager::orderBy('created_at','desc')->get(['name','email','role','event_code','validated']);

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
			'password' => 'required|min:6',
			'role' => 'numeric',
			);
		$validator = Validator::make(Input::all(), $rules);

		$validator->sometimes('event_code', 'required|alpha_num', function($input)
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
		$manager->event_code = Input::get('event_code',NULL);
		$manager->validated = true;
		$manager->save();

		Session::flash('success', 'Manager added successfully.');
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

		return $parent_id;
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

		return View::make('events_edit',array(
			'event_categories' => $event_categories,
			'page_type' => 'new'
			));
	}

	public function eventsEdit(){
		return 'yo';
	}

	public function eventsSave(){
		return Input::all();

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


	private function getRoleName($role){
		switch ($role) {

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

			case 21:
				return 'Super Admin'; //All features
				break;
			

			default:
				return 'Unknown'; //Unknown number in role.
				break;
		}

	}
}