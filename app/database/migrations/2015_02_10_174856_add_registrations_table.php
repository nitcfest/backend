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
		    $table->string('email', 50)->unique();
		    $table->string('password');
		    
		    $table->string('name', 100);
		    $table->string('phone', 100);
		    
		    $table->integer('college')->unsigned();
		    $table->foreign('college')->references('id')->on('colleges');

		    $table->integer('runtime_id')->nullable();
		    $table->boolean('payment_done')->default(false);
		    $table->boolean('hospitality_start')->default(false);
		    $table->boolean('hospitality_end')->default(false);

		    $table->string('notes', 1000)->nullable();

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
