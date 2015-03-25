<?php


class TeamMember extends Eloquent
{
	protected $table = 'team_members';
	public $timestamps = false;

	public function team(){
		return $this->belongsTo('Team')->select('id', 'event_code', 'team_code', 'owner_id', 'confirmation');
	}

	public function details(){
		return $this->belongsTo('Registration','registration_id')->select('id', 'name', 'email', 'phone', 'college_id', 'registration_confirm','hospitality_type');
	}

}