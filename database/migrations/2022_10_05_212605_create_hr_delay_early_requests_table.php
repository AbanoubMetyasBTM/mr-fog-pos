<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrDelayEarlyRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('hr_delay_early_requests')) {
            return;
        }

		Schema::create('hr_delay_early_requests', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('employee_id');
			$table->string('req_type', 50)->comment('delay_request, early_leave');
			$table->string('req_title', 300);
			$table->text('req_desc');
			$table->date('req_date');
			$table->time('req_wanted_time');
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
		Schema::drop('hr_delay_early_requests');
	}

}
