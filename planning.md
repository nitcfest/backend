Ideas for the API.  
  
Please add suggestions, ideas. Feel free to edit.  
  
Public API  
==========  
Base URL: http://www.ragam.org.in  
Prefix: /api/  
  
If no method specified ==> GET  
  
/  
	@Text used on the homepage that's intended to be edited  
	@Names of other endpoints  
  
/events  
	@Event Categories, and number of events in each.  
	/events/{category}  
		@List of events  
			/events/{category}/{event}  
				@Details of the event  
  
/workshops  
	@Names of workshops  
	/workshops/{name}  
		@Details of the workshop  
  
/proshows  
	@Names of proshows  
	/proshows/{name}  
		@Details of the proshow  
  
/users  
	@Details of the current user, show empty if not logged in.  
  
/users/10245  
	@Details of the user, available only if logged in.  
  
/teams  
	@Details of teams the user is part of. Empty if not logged in.  
  
  
/users/10245/events  
/users/10245/workshops  
/users/10245/proshows  
	@Name of events, workshops, proshows registered by user, user should be logged in.  
  
POST /users/10245/events/street_play  
	@Register for street play  
  
POST /users/10245/workshops/hacking  
	@Register for hacking workshop  
  
DELETE /users/10245/events/street_play  
	@Deregister from street play  
  
POST /teams/204/10245  
	@Add user 10245 to team 204  
  
  
