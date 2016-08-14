<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('relations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('family_id');
			$table->integer('person_id');
			$table->string('relation');
			$table->integer('relative_id');
			$table->timestamps();
			// $table->foreign('user_id')->references('id')->on('users');
			// $table->foreign('family_id')->references('id')->on('families');
			// $table->foreign('person_id')->references('id')->on('persons');
			// $table->foreign('relative_id')->references('id')->on('persons');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('relations');
	}

}
