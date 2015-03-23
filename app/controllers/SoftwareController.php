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



	public function studentRegistrationDo(){

		$ids = json_decode(Input::get('ids'));
		
		$registrations = Registration::with('college')->whereIn('id',$ids)->get();

		$registrations->map(function($registration){
			//Read Team Members
		});

		return $registrations;

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