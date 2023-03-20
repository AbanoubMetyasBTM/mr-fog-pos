<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTaskCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('employee_task_comments')) {
            return;
        }

		Schema::create('employee_task_comments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('task_id');
			$table->integer('employee_id');
			$table->text('task_comment');
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
		Schema::drop('employee_task_comments');
	}

}
