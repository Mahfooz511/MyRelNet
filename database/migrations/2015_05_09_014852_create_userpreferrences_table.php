<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserpreferrencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('userpreferrences', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id') ;
			$table->string('lang')->default('Hindi') ; // Hindi , English, Urdu
			$table->integer('preferredfamily_id')->nullable() ; 
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
		Schema::drop('userpreferrences');
	}

}
