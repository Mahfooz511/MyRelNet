<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamilyaccessesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('familyaccesses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('family_id');
			$table->string('aceess_type'); //owner, edit, view
			$table->integer('root_id'); // root person for this family
			$table->integer('your_id')->nullable(); //person-id of this user in this family 
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
		Schema::drop('familyaccesses');
	}

}
