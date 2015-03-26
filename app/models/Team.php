<?php


class Team extends Eloquent
{
	protected $table = 'teams';
	public $timestamps = false;

	public function team_members(){
		return $this->hasMany('TeamMember')->select('team_id','registration_id');
	}

	public function event(){
		return $this->belongsTo('Events', 'event_code', 'event_code')->select('event_code','name','category_id','team_min','team_max','registration_enabled');
	}

	public function owner(){
		return $this->belongsTo('Registration','owner_id')->select('id', 'name');
	}
}