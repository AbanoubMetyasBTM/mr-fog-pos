<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeActionLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists("employee_action_log");

        Schema::create('employee_action_log', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id');
			$table->string('module', 50);
			$table->string('action_url', 500);
			$table->string('action_type', 50);
			$table->text('old_obj');
			$table->text('request_headers');
			$table->text('request_body');
			$table->string('log_desc', 300);
			$table->dateTime('logged_at');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('employee_action_log');
	}

}
