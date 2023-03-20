<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrHolidayRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('hr_demand_vacation_requests')) {
            DB::statement("DROP TABLE `hr_demand_vacation_requests`;");
        }

	    if (Schema::hasTable('hr_sick_holidays_requests')) {
            DB::statement("DROP TABLE `hr_sick_holidays_requests`;");
        }


	    if (Schema::hasTable('hr_holiday_requests')) {
            return;
        }

		Schema::create('hr_holiday_requests', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('employee_id');
			$table->integer('req_type')->comment('vacation, sick_holiday');
			$table->string('req_title', 300);
			$table->text('req_desc');
			$table->date('req_date');
			$table->boolean('req_is_accepted');
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
		Schema::drop('hr_holiday_requests');
	}

}
