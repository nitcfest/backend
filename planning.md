Plans for the API.

Public API
==========
http://www.ragam.org.in/2015/cms/api/

If no method specified ==> GET
    
    /
    	@Text used on the homepage that's intended to be edited
    	@Names of other endpoints
    
    /events
        Categories and events in each category

    /event/{event_code}
        Details of an event

    
    /user
        Details of the current user including registrations, teams.
    
    POST /user/login
        Log in the user
    
    /user/logout
        Log out.

    POST /user/register
        Sign-up.


    Left to do:
        Register for an event
            => Create team, add members to team.

        Edit/Cancel Registration
            => Remove members, delete team