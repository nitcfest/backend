<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegistrationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('registrations', function($table)
		{	
		    $table->increments('id');
		    $table->bigInteger('fb_uid')->nullable();

		    $table->string('email', 50)->unique()->nullable();
		    $table->string('password')->nullable();
		    
		    $table->string('name', 100)->nullable();
		    $table->string('phone', 100)->nullable();
		    
		    $table->integer('college_id')->unsigned()->nullable();
		    $table->foreign('college_id')->references('id')->on('colleges');

		    $table->integer('hospitality_type')->unsigned()->default(0);
		    $table->boolean('payment_done')->default(false);

		    $table->string('notes', 1000)->nullable();

		    $table->rememberToken();
		    $table->timestamps();
		});

		DB::update("ALTER TABLE registrations AUTO_INCREMENT = 10001;");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('registrations');
	}

}
