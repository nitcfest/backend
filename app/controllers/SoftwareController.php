<?php


class SoftwareController extends BaseController {

	public function statistics(){

		//Make sure you enter the correct id as in the event_categories table.
		$workshops_id = 2;

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


	public function studentRegistrationDo(){

		$ids = json_decode(Input::get('ids'));
		
		$registrations = Registration::with('college')->whereIn('id',$ids)->get();

		return $registrations;

	}



	public function eventRegistration(){
		return View::make('software.event_registration');
	}

	public function workshopRegistration(){
		return View::make('software.workshop_registration');
	}

	public function hospitalityManager(){
		return View::make('software.hospitality_manager');
	}





}