<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManagersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('managers', function($table)
		{	
		    $table->increments('id');
		    $table->string('email', 50)->unique();
		    $table->string('password');
		    
		    $table->integer('role')->default(0);
		    $table->string('event_code', 10)->nullable();
		    $table->boolean('validated')->default(false);

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
		Schema::drop('managers');
	}

}
