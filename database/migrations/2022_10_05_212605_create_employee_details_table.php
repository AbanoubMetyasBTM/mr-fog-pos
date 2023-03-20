<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('employee_details')) {
            return;
        }

		Schema::create('employee_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('employee_id');
			$table->text('employee_working_days');
			$table->integer('employee_required_working_hours_per_day');
			$table->decimal('employee_salary', 10);
			$table->decimal('employee_overtime_hour_rate', 10)->comment('it could be 1.5 or 2');
			$table->integer('employee_vacation_hour_rate')->comment('if the emp works at vacation then his hour should be like 1.5 normal hour');
			$table->time('employee_shoud_start_work_at');
			$table->time('employee_shoud_end_work_at');
			$table->integer('employee_sick_leave_max_requests');
			$table->integer('employee_delay_requests_max_requests');
			$table->integer('employee_vacation_max_requests');
			$table->string('create_order_pin_number', 250);
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
		Schema::drop('employee_details');
	}

}
