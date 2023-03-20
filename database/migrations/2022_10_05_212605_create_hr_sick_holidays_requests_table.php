<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrSickHolidaysRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('hr_sick_holidays_requests')) {
            return;
        }

		Schema::create('hr_sick_holidays_requests', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('employee_id');
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
		Schema::drop('hr_sick_holidays_requests');
	}

}
