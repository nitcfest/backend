<?php
 
class InitialSeeder extends Seeder {
 
    public function run()
    {
        DB::table('managers')->truncate();
        $manager = array(
            'email' => 'xaneem@gmail.com',
            'name'=> 'Saneem',
            'password' => Hash::make('nimda'),
            'role' => 21,
            'validated' => true,
            'created_at' => date("Y-m-d G:i:s"),
            'updated_at' => date("Y-m-d G:i:s"),
        );   
        DB::table('managers')->insert($manager);


        DB::table('event_categories')->truncate();
        $event_categories = array(
            ['name' => 'Events', 'parent_id' => 0],
            ['name' => 'Workshops', 'parent_id' => 0],
            ['name' => 'Proshows', 'parent_id' => 0],

            ['name' => 'General', 'parent_id' => 1],
            ['name' => 'Literay', 'parent_id' => 1],
            ['name' => 'Dance', 'parent_id' => 1],
        );
        DB::table('event_categories')->insert($event_categories);


        DB::table('events')->truncate();
        $event = array(
            'event_code' => 'XYZ',
            'category_id' => 4,
            'name' => 'Sample Event',
            'tags' => 'dolor sit amet',
            'short_description' => 'This is a short short description of a sample event.',
            'long_description' => 'This is the long description of a sample event.',
            'team_min' => 1,
            'team_max' => 1,
            'validated' => false,
            'created_at' => date("Y-m-d G:i:s"),
            'updated_at' => date("Y-m-d G:i:s"),
        );
        DB::table('events')->insert($event);        


        DB::table('colleges')->truncate();
        $college = array(
            'name' => 'NIT Calicut',
            'validated' => false,
            'created_at' => date("Y-m-d G:i:s"),
            'updated_at' => date("Y-m-d G:i:s"),
        );
        DB::table('colleges')->insert($college);     


        DB::table('registrations')->truncate();
        DB::update("ALTER TABLE registrations AUTO_INCREMENT = 10001;");
        
        $registration = array(
            'email' => 'user@example.com',
            'password' => Hash::make('nimda'),
            'name' => 'John Doe',
            'phone' => '9995552233',
            'college_id' => 1,
            'created_at' => date("Y-m-d G:i:s"),
            'updated_at' => date("Y-m-d G:i:s"),
        );
        DB::table('registrations')->insert($registration);      


        DB::table('teams')->truncate();
        $team = array(
            'event_code' => 'XYZ',
            'team_code' => 101,

            'registration_id' => 1,
            'created_at' => date("Y-m-d G:i:s"),
        );
        DB::table('teams')->insert($team);    


        DB::table('team_members')->truncate();
        $team_member = array(
            'team_id' => 1,
            'registration_id' => 1,
            'created_at' => date("Y-m-d G:i:s"),
        );
        DB::table('team_members')->insert($team_member);    

    }
 
}