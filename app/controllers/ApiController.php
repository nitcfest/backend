<?php

class ApiController extends BaseController {


	public function index()
	{
		return Response::json(array(
			'status'=>'active',
			'contacts' => array(
				'Cultural Secretary' => array('Abdul Wasih', '+91-1122334455', 'wasih@ragam.org.in'),
				'Somebody' => array('Someone', '+91-1234567890', 'someone@ragam.org.in'),
				),

			'updates' => array(
				'This is the latest message',
				'This is somewhat new',
				'This is the oldest'
				),

			));

	}


	public function events(){
		//Get base category
		$categories = EventCategories::where('parent_id','=',0)->get();

		$categories->map(function($category){
			//get childrens
			$sub = EventCategories::where('parent_id', '=', $category->id)->get();

			if($sub->count()>0)
				$category->sub_categories = $sub;

			$sub->map(function($sub_cat){
				$events = Events::where('category_id','=',$sub_cat->id)->whereValidated(true)->get(['event_code', 'name', 'tags', 'prizes', 'short_description', 'team_min', 'team_max']);

				if($events->count()>0)
					$sub_cat->events = $events;

				return $sub_cat;
			});

			$events = Events::where('category_id','=',$category->id)->whereValidated(true)->get(['event_code', 'name', 'tags', 'prizes', 'short_description', 'team_min', 'team_max']);

			if($events->count()>0)
				$category->events = $events;


			return $category;

		});

		return $categories;
	}


	public function event($code){
		$event = Events::where('event_code','=',$code)->get();

		if($event->count() == 0)
			return Response::json(['response'=>'error','reason'=>'no_event']);

		return $event;
	}



}
