Managers
	id
	role
		0 => Printing Only


			Super-admin
			 Website Admin

			 Overall Events Manager
			 Event Manager
			 
			 Registration Desk
			 Results Entry
			 Printing Only
			 and more..

	event_code => only for event managers

	email
	password
	validate


Event Categories
	id
	parent_id => 0 for parents
	name


Events
	id
	event_code
	category -> references categories table
		workshops
		proshows
		social initiatives
		exhibitions
		general
		literary
		dance
		music
	name
	tags
	contacts
	
	prize
	team_min
	team_max

	short_description
	long_description
	validated ==> boolean


Colleges
	id
	name
	validated ==> boolean

Registrations
	id => Start from 10001
	email ==> unique
	password
	added_at

	name
	phone
	college_id


	payment_done ==> boolean
	accomodation_used ==> boolean
	notes ==> text


Teams
	id
	event_code
	team_code
	registration_id //Owner
	added_at

Team Members
	id
	team_id
	registration_id



