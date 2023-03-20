<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('employee_tasks')) {
            return;
        }

		Schema::create('employee_tasks', function(Blueprint $table)
		{
			$table->integer('task_id', true);
			$table->integer('employee_id');
			$table->string('task_title', 500);
			$table->text('task_desc');
			$table->dateTime('task_deadline');
			$table->string('task_status', 50)->comment('pending, working, done');
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
		Schema::drop('employee_tasks');
	}

}
