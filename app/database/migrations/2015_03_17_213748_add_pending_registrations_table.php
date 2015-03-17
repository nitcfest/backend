<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPendingRegistrationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pending_registrations', function($table)
		{	
		    $table->increments('id');
		    $table->string('name', 100)->nullable();
		    $table->string('email', 50)->nullable();
		    $table->string('phone', 50)->nullable();

		    $table->integer('college_id')->unsigned()->nullable();
		    $table->foreign('college_id')->references('id')->on('colleges');

		    $table->integer('status')->nullable()->default(0);
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
		Schema::drop('pending_registrations');
	}

}