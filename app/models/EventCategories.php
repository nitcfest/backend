<?php


class EventCategories extends Eloquent
{
	protected $table = 'event_categories';
	public $timestamps = false;


	public function events(){
		return $this->hasMany('Events','category_id');
	}

}