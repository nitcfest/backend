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
			$registration->registration_confirm = 1;

			$registration->hospitality_type = Input::get('hospitality_type', '');
			
			if(Input::get('hospitality_type', '') == 1 || Input::get('hospitality_type', '') == 2)			
				$registration->hospitality_confirm = 1;

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

			$rules = array(
				'id' => 'required|numeric|exists:registrations,id',
				'name' => 'required|min:3',
				'college_id' => 'required|numeric|exists:colleges,id,validated,1',
				'hospitality_type' => 'required|in:0,1,2',
				'phone' => 'required|min:10|max:15'
				);

			$validator = Validator::make(Input::all(), $rules);

			$validator->sometimes('email', 'required|email', function($input)
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


			$registration = Registration::where('id','=',Input::get('id'))->first();
			$registration->name = Input::get('name', '');

			if(Input::get('email', '') != '')
				$registration->email = Input::get('email');

			$registration->phone = Input::get('phone', '');
			$registration->college_id = Input::get('college_id', '');
			$registration->hospitality_type = Input::get('hospitality_type', '');

			$registration->registration_confirm = 1;		
			if(Input::get('hospitality_type', '') == 1 || Input::get('hospitality_type', '') == 2)			
				$registration->hospitality_confirm = 1;

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
			
			
		}else{
			return Response::json(['result'=>'fail']);
		}
	}



	public function studentRegistrationDo(){

		$ids = json_decode(Input::get('ids'));
		
		$registrations = Registration::with('college')->whereIn('id',$ids)->get();

		$registrations = $registrations->filter(function($registration){
			if($registration->registration_confirm == 1)
				return 0;
			return 1;
		});

		if($registrations->count() == 0)
			return Redirect::route('software_student_registration');


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


		$selected_teams->map(function($team){
			$confirmed = 1;
			$team->team_members->each(function($member) use(&$confirmed){
				if($member->details->registration_confirm == 0)
					$confirmed = 0;
			});

			$team->confirmable = $confirmed;
			return $team;			
		});

		return View::make('software.event_registration', array('registrations'=>$selected_teams));
	}


	public function eventRegistrationConfirm(){
		$team_id = Input::get('team_id');

		$team = Team::with('team_members.details')->where('id','=',$team_id)->first();

		if($team->count() == 0)
			return Response::json(['result'=>'fail']);

		$confirmed = 1;
		$team->team_members->each(function($member) use(&$confirmed){
			if($member->details->registration_confirm == 0)
				$confirmed = 0;
		});

		if($confirmed == 1){
			$team->confirmation = 1;
			$team->save();

			return Response::json(['result'=>'success']);			
		}else{
			return Response::json(['result'=>'fail']);
		}
	}

	public function eventRegistrationConfirmGet(){
		$team_id = Input::get('id');

		$team = Team::with('team_members.details')->where('id','=',$team_id)->first();

		if($team->count() == 0)
			return Redirect::route('software_event_registration_details', $team_id);

		$confirmed = 1;
		$team->team_members->each(function($member) use(&$confirmed){
			if($member->details->registration_confirm == 0)
				$confirmed = 0;
		});

		if($confirmed == 1){
			$team->confirmation = 1;
			$team->save();

			return Redirect::route('software_event_registration_details', $team_id);			
		}else{
			return Redirect::route('software_event_registration_details', $team_id);
		}

	}

	public function eventRegistrationDetails($id)
	{
		$team = Team::with('owner','event','team_members.details.college')->whereId($id)->get();

		$workshops_id = $this->workshops_id();

		if($team->count()==0){
			return Redirect::route('software_event_registration');
		}

		$team = $team->first();

		if($team->event->category_id == $workshops_id){
			return Redirect::route('software_event_registration');
		}


		$confirmed = 1;
		$team->team_members->each(function($member) use(&$confirmed){
			if($member->details->registration_confirm == 0)
				$confirmed = 0;
		});

		$team->confirmable = $confirmed;


		return View::make('software.event_registration_details',array('team'=>$team));
	}


	public function eventRegistrationDetailsEdit($id)
	{
		$team = Team::with('owner','event','team_members.details.college')->whereId($id)->get();

		$workshops_id = $this->workshops_id();

		if($team->count()==0){
			return Redirect::route('software_event_registration');
		}

		$team = $team->first();

		if($team->event->category_id == $workshops_id){
			return Redirect::route('software_event_registration');
		}

		if($team->confirmation == 1)
			return Redirect::route('software_event_registration_details', $id);


		$confirmed = 1;
		$team->team_members->each(function($member) use(&$confirmed){
			if($member->details->registration_confirm == 0)
				$confirmed = 0;
		});

		$team->confirmable = $confirmed;


		return View::make('software.event_registration_details_edit',array('team'=>$team));
	}


	public function eventRegistrationRemoveMember(){
		$team_id = Input::get('team_id','');
		$member_id = Input::get('member_id','');

		$team_members = TeamMember::where('team_id','=', $team_id)->get();

		if($team_members->count() > 1){
			$delete = TeamMember::where('team_id','=', $team_id)->where('registration_id','=', $member_id)->first()->delete();		
		}

		return Redirect::route('software_event_registration_details_edit',$team_id);
	}

	public function eventRegistrationAddMember(){
		$team_id = Input::get('team_id');
		$team_members = Input::get('team_members');

		$existing_members = TeamMember::where('team_id','=',$team_id)->lists('registration_id');

		if(count($team_members) == 0)
			return Redirect::route('software_event_registration_details_edit', $team_id);		


		$selected_members = array();

		foreach ($team_members as $member_id) {
			if(!in_array($member_id, $existing_members)){
				array_push($selected_members, $member_id);
			}
		}

		foreach ($selected_members as $member_id) {
			$team_member = new TeamMember;
			$team_member->team_id = $team_id;
			$team_member->registration_id = $member_id;
			$team_member->save();
		}

		return Redirect::route('software_event_registration_details_edit', $team_id);		
	}

	public function eventRegistrationNew(){
		$events = Events::with('category')->where('validated','=',1)->get(array('event_code','category_id','name','team_min','team_max'));

		//ID of events in event_categories
		$events_id = 1;
		$workshops_id = $this->workshops_id();

		$selected_events = $events->filter(function($event) use($workshops_id, $events_id){
			if($event->category_id != $workshops_id && $event->category->parent_id == $events_id )
				return 1;

			return 0;
		});

		return View::make('software.event_registration_new', array('events'=>$selected_events));
	}

	public function eventRegistrationNewPost(){
		$event_code = Input::get('event_code','');
		$team_members = Input::get('team_members','');

		$events = Events::with('category')->where('validated','=',1)->where('event_code','=',$event_code)->get(array('event_code','category_id'));

		if($events->count() == 0 || !is_array($team_members) || count($team_members) == 0){
			Session::flash('error', 'Invalid event or no team members selected.');
			return Redirect::route('software_event_registration_new');
		}

		$event = $events->first();

		//ID of events in event_categories
		$events_id = 1;
		$workshops_id = $this->workshops_id();

		if($event->category_id == $workshops_id || $event->category->parent_id != $events_id ){
			Session::flash('error', 'Select a valid event.');
			return Redirect::route('software_event_registration_new');
		}


		$owner_id = $team_members[0];

		foreach ($team_members as $member_id) {
			if($this->isRegisteredForEvent($member_id, $event_code)){
				Session::flash('error', Config::get('app.id_prefix').$member_id.' is already registered for this event in another team.');
				return Redirect::route('software_event_registration_new');
			}
		}

		$existing_team = Team::where('event_code','=',$event_code);

		if($existing_team->count() == 0){
			$new_team_code = 101;
		}else{
			$new_team_code = $existing_team->orderBy('team_code','desc')->first()->team_code + 1;
		}

		$team = new Team;
		$team->event_code = $event_code;
		$team->team_code = $new_team_code;
		$team->owner_id = $owner_id;
		$team->save();

		foreach ($team_members as $member_id) {
			$team_member = new TeamMember;
			$team_member->team_id = $team->id;
			$team_member->registration_id = $member_id;
			$team_member->save();
		}

		return Redirect::route('software_event_registration_details', $team->id);
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

	public function workshopRegistrationDetails($id)
	{
		$team_id = $id;

		$team = Team::with('team_members.details.college','event')->where('id','=',$team_id)->get();

		$workshops_id = $this->workshops_id();

		if($team->count()==0){
			return Redirect::route('software_workshop_registration');
		}

		$team = $team->first();

		if($team->event->category_id != $workshops_id){
			return Redirect::route('software_workshop_registration');
		}


		return View::make('software.workshop_registration_details', array('team'=>$team));
	}


	public function workshopRegistrationConfirm(){
		$team_id = Input::get('id');

		$team = Team::with('team_members.details.college','event')->where('id','=',$team_id)->get();

		$workshops_id = $this->workshops_id();

		if($team->count()==0){
			return Redirect::route('software_workshop_registration');
		}

		$team = $team->first();

		if($team->event->category_id != $workshops_id){
			return Redirect::route('software_workshop_registration');
		}

		$team->confirmation = 1;
		$team->save();

		return Redirect::route('software_workshop_registration_details',$team_id);
	}

	public function workshopRegistrationNew(){
		$events = Events::with('category')->where('validated','=',1)->get(array('event_code','category_id','name','team_min','team_max'));

		$workshops_id = $this->workshops_id();

		$selected_events = $events->filter(function($event) use($workshops_id){
			if($event->category_id == $workshops_id)
				return 1;

			return 0;
		});

		return View::make('software.workshop_registration_new', array('events'=>$selected_events));
	}

	public function workshopRegistrationNewPost(){
		$event_code = Input::get('event_code','');
		$team_members = Input::get('team_members','');

		$events = Events::with('category')->where('validated','=',1)->where('event_code','=',$event_code)->get(array('event_code','category_id'));

		if($events->count() == 0 || !is_array($team_members) || count($team_members) == 0){
			Session::flash('error', 'Invalid workshop or no team members selected.');
			return Redirect::route('software_workshop_registration_new');
		}

		$event = $events->first();

		$workshops_id = $this->workshops_id();

		if($event->category_id != $workshops_id){
			Session::flash('error', 'Select a valid workshop.');
			return Redirect::route('software_workshop_registration_new');
		}


		$owner_id = $team_members[0];

		foreach ($team_members as $member_id) {
			if($this->isRegisteredForEvent($member_id, $event_code)){
				Session::flash('error', Config::get('app.id_prefix').$member_id.' is already registered for this workshop.');
				return Redirect::route('software_workshop_registration_new');
			}
		}

		$existing_team = Team::where('event_code','=',$event_code);

		if($existing_team->count() == 0){
			$new_team_code = 101;
		}else{
			$new_team_code = $existing_team->orderBy('team_code','desc')->first()->team_code + 1;
		}

		$team = new Team;
		$team->event_code = $event_code;
		$team->team_code = $new_team_code;
		$team->owner_id = $owner_id;
		$team->confirmation = 1;
		$team->save();

		foreach ($team_members as $member_id) {
			$team_member = new TeamMember;
			$team_member->team_id = $team->id;
			$team_member->registration_id = $member_id;
			$team_member->save();
		}

		return Redirect::route('software_workshop_registration_details', $team->id);
	}






	public function hospitalityManager(){
		return View::make('software.hospitality_manager');
	}




	protected function isRegisteredForEvent($registration_id, $event_code){
		//returns true if user is registered for the event

		$query = TeamMember::where('registration_id','=', $registration_id)
					->with('team')
					->whereHas('team', function($q) use($event_code){
						    $q->where('event_code', '=', $event_code);
						});

		if($query->count()==0){
			return false;
		}else{
			return true;
		}
	}


}