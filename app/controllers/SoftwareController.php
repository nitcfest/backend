<?php


class SoftwareController extends BaseController {

	protected function workshops_id(){
		//Make sure you enter the correct id as in the event_categories table.
		return 2;
	}


	public function statistics(){
		$workshops_id = $this->workshops_id();

		$reg_confirm_count = Registration::where('registration_confirm','=',1)->count();
		$hos_confirm_count = Registration::where('registration_confirm','=',1)->where('hospitality_confirm','=',1)->count();

		$events_confirm_count = 0;
		$workshops_confirm_count = 0;

		$teams_confirmed = Team::with('event')->where('confirmation','=',1)->get();

		$teams_confirmed->map(function($team) use($workshops_id, &$events_confirm_count, &$workshops_confirm_count){
			if($team->event->category_id == $workshops_id)
				$workshops_confirm_count++;
			else
				$events_confirm_count++;
		});

		$data = array(
			'registration' => $reg_confirm_count,
			'hospitality'  => $hos_confirm_count,
			'events' => $events_confirm_count,
			'workshops' => $workshops_confirm_count);

		return View::make('software.statistics', $data);
	}

	public function studentRegistration(){

		$registrations = Registration::with('college')->whereNotNull('college_id')->get();

		return View::make('software.student_registration', array('registrations' => $registrations ));
	}

	public function studentRegistrationDetails($id){
		$registration = Registration::with('college')->where('id','=',$id);

		if($registration->count() == 0)
			return Redirect::route('software_student_registration');

		$registration = $registration->first();


		$team_members = TeamMember::with('team.event')->where('registration_id','=', $registration->id)->get();

		// return $team_members;


		return View::make('software.student_registration_details', array(
			'registration' => $registration,
			'teams' => $team_members,
			 ));
	}


	public function studentRegistrationNew(){
		return View::make('software.student_registration_new');
	}


	public function studentRegistrationSave(){
		$type = Input::get('type', '');

		if($type == 'new'){

			$rules = array(
				'name' => 'required|min:3',
				'college_id' => 'required|numeric|exists:colleges,id,validated,1',
				'hospitality_type' => 'required|in:0,1,2',
				'phone' => 'required|min:10|max:15'
				);

			$validator = Validator::make(Input::all(), $rules);

			$validator->sometimes('email', 'required|email|unique:registrations', function($input)
			{
			    return $input->email != '';
			});


			if($validator->fails())
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
			$registration->name = Input::get('name', '');

			if(Input::get('email', '') != '')
				$registration->email = Input::get('email');

			$registration->phone = Input::get('phone', '');
			$registration->college_id = Input::get('college_id', '');
			$registration->hospitality_type = Input::get('hospitality_type', '');
			$registration->save();
			

			if($registration->hospitality_type == 0)
				$hospitality = 'No Accomodation';
			else if($registration->hospitality_type == 1)
				$hospitality = 'Yes, Male';
			else
				$hospitality = 'Yes, Female';

			if($registration->hospitality_type > 0)
				$hospitality_yn = 'yes';
			else
				$hospitality_yn = 'no';

			return Response::json([
				'result'=>'success',
				'name'=> $registration->name,
				'email' => $registration->email,
				'phone' => $registration->phone,
				'id' => $registration->id,
				'college' => $registration->college->name,
				'hospitality' => $hospitality,
				'hospitality_yn' => $hospitality_yn,
			]);


		}else if($type == 'confirm'){

			return Response::json([
				'result'=>'success',
				'name'=>'hello',
				'email' => 'fuckflemil',
				'phone' => '980980',
				'id' => 'shit me',
				'college' => 'NIIIIIT',
				'hospitality' => 'Fuck you.',
				'hospitality_yn' => 'no',
				]);
			
			
		}else{
			return Response::json(['result'=>'fail']);
		}
	}



	public function studentRegistrationDo(){

		$ids = json_decode(Input::get('ids'));
		
		$registrations = Registration::with('college')->whereIn('id',$ids)->get();

		return View::make('software.student_registration_do', array('registrations'=>$registrations));
	}



	public function eventRegistration(){
		$teams = Team::with('event','team_members.details')->orderBy('event_code','asc')->orderBy('team_code','asc')->get();


		$workshops_id = $this->workshops_id();

		$selected_teams = $teams->filter(function($team) use($workshops_id){
			if($team->event->category_id == $workshops_id)
				return 0;

			return 1;
		});

		return View::make('software.event_registration', array('registrations'=>$selected_teams));
	}

	public function workshopRegistration(){
		$teams = Team::with('event','team_members.details')->orderBy('event_code','asc')->orderBy('team_code','asc')->get();


		$workshops_id = $this->workshops_id();

		$selected_teams = $teams->filter(function($team) use($workshops_id){
			if($team->event->category_id == $workshops_id)
				return 1;

			return 0;
		});
		
		return View::make('software.workshop_registration', array('registrations'=>$selected_teams));
	}

	public function hospitalityManager(){
		return View::make('software.hospitality_manager');
	}





}