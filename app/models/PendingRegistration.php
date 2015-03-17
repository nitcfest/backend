<?php


class PendingRegistration extends Eloquent
{
	protected $table = 'pending_registrations';

	public function college(){
		return $this->belongsTo('College')->select('id', 'name', 'validated');
	}
}