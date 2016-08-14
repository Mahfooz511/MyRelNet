<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shares', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('family_id');
			$table->string('type'); //single, group
			$table->integer('validity'); // 1=week, 2=2 week, 3=month
			$table->string('shareid', 50);
			$table->boolean('valid')->default(1); // 1=yes / 2=no
			$table->string('access_type'); // Edit, View
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
		Schema::drop('shares');
	}

}
