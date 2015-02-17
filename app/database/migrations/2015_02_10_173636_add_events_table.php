<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function($table)
		{
		    $table->increments('id');
		    $table->string('event_code', 10)->unique();

		   	$table->integer('category_id')->unsigned();
		   	$table->foreign('category_id')->references('id')->on('event_categories');

		   	$table->string('name', 100);

		   	$table->string('tags', 1000)->nullable();
		   	$table->string('contacts', 3000)->nullable();
		   	$table->string('prizes', 3000)->nullable();
		   	$table->string('short_description', 3000)->nullable();
		   	$table->string('long_description', 10000)->nullable();

		   	$table->integer('team_min')->unsigned()->default(1);
		   	$table->integer('team_max')->unsigned()->default(1);

		   	$table->boolean('validated')->default(false);

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
		Schema::drop('events');
	}

}

