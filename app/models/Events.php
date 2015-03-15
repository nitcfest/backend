<?php


class Events extends Eloquent
{
	protected $table = 'events';
	// public $timestamps = false;

	public function category(){
		return $this->belongsTo('EventCategories','category_id');
	}

}