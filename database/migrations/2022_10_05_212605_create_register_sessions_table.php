<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterSessionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('register_sessions')) {
            return;
        }

		Schema::create('register_sessions', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('register_id');
			$table->integer('employee_id');
			$table->dateTime('register_start_at');
			$table->decimal('register_start_money', 10);
			$table->dateTime('register_closed_at');
			$table->decimal('register_closed_money', 10);
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('register_sessions');
	}

}
