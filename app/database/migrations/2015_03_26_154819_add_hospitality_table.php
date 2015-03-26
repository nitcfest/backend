<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHospitalityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hospitality', function($table)
		{	
		    $table->increments('id');

		    $table->integer('captain_id')->unsigned();
		    $table->foreign('captain_id')->references('id')->on('registrations');

		    $table->integer('registration_id')->unsigned();
		    $table->foreign('registration_id')->references('id')->on('registrations');

		    $table->string('location',30)->nullable();
		    $table->string('room_no',30)->nullable();
		    $table->string('bed_no',30)->nullable();

		    $table->integer('checkout')->default(0);
		    $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hospitality');
	}

}
