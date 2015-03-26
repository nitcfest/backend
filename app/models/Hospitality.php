<?php


class Hospitality extends Eloquent
{
	protected $table = 'hospitality';

	public function registration(){
		return $this->belongsTo('Registration','registration_id');
	}

	public function captain(){
		return $this->belongsTo('Registration','captain_id')->select('id', 'name');
	}
}