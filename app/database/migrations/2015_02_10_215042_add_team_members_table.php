<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeamMembersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('team_members', function($table)
		{	
		    $table->increments('id');

		    $table->integer('team_id')->unsigned();
		    $table->foreign('team_id')->references('id')->on('teams');

		    $table->integer('registration_id')->unsigned();
		    $table->foreign('registration_id')->references('id')->on('registrations');

		    $table->timestamp('created_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('team_members');
	}

}
