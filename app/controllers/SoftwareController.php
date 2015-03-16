<?php


class SoftwareController extends BaseController {

	public function hospitality(){

		//ID of NIT Calicut in colleges table
		$nit_id = 2;

		$males = Registration::where('hospitality_type','=', 1)->where('college_id','!=',$nit_id)->orderBy('id','asc')->get();
		$females = Registration::where('hospitality_type','=', 2)->where('college_id','!=',$nit_id)->orderBy('id','asc')->get();

		return View::make('software.hospitality', array('males'=>$males, 'females'=>$females));
	}



}