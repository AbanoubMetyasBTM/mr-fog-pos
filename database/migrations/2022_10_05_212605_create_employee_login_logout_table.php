<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLoginLogoutTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('employee_login_logout')) {
            return;
        }

		Schema::create('employee_login_logout', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('employee_id');
			$table->date('work_date');
			$table->time('login_time');
			$table->time('logout_time');
			$table->time('working_hours');
			$table->time('remain_hours');
			$table->time('overtime_hours');
			$table->boolean('work_day_is_general_holiday');
			$table->boolean('work_day_is_demanded_holiday')->comment('يوم تم طلبه اجازة');
			$table->boolean('work_day_has_sick_leave');
			$table->boolean('work_day_has_delay_request');
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
		Schema::drop('employee_login_logout');
	}

}
