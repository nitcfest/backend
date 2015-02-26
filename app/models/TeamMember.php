<?php


class TeamMember extends Eloquent
{
	protected $table = 'team_members';
	public $timestamps = false;

	public function team(){
		return $this->belongsTo('Team')->select('id', 'event_code', 'team_code', 'owner_id');
	}

	public function details(){
		return $this->belongsTo('Registration','registration_id')->select('id', 'name');
	}

}