<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResultsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('results', function($table)
		{	
		    $table->increments('id');
		    $table->string('event_code', 10)->nullable();
		    $table->integer('order_no')->default(1);

		    $table->string('position', 100)->nullable();
		    $table->string('college', 500)->nullable();
		    $table->string('team_id', 100)->nullable();
		    $table->string('team_members', 2000)->nullable();

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
		Schema::drop('results');
	}

}
