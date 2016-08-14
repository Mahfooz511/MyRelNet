<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('persons', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id') ;
			$table->integer('family_id');
			$table->string('name',120)->nullable() ;
			$table->string('nickname',120);
			$table->string('gender',6) ;
			$table->integer('age')->nullable();
			$table->integer('siblingno')->nullable();
			$table->string('image',180)->nullable();
			$table->string('location',180)->nullable();
			$table->string('deadoralive',6)->default('alive') ;
			$table->integer('generation') ;
			$table->string('facebookid',120)->nullable();
			$table->string('googleid',120)->nullable();
			$table->string('email_id',120)->nullable();
			$table->text('description')->nullable() ;
			$table->timestamps();
			// $table->foreign('user_id')->references('id')->on('users');
			// $table->foreign('family_id')->references('id')->on('families');
		});
	}

		

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('persons');
	}

}
