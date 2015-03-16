<?php


class SoftwareController extends BaseController {

	public function hospitality(){
		$males = Registration::where('hospitality_type','=', 1)->orderBy('id','asc')->get();
		$females = Registration::where('hospitality_type','=', 2)->orderBy('id','asc')->get();

		return View::make('software.hospitality', array('males'=>$males, 'females'=>$females));
	}



}