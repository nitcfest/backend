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


Events
	id
	code
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

Event Categories
	id
	parent_id => 0 for parents
	name

Colleges
	id
	name
	validated ==> boolean

Student Accounts
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
	event_id
	team_code
	student_id //Owner
	added_at

Team Members
	id
	registration_id
	student_id



