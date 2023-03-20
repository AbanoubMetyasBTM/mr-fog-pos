<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrPaycheckTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('hr_paycheck')) {
            return;
        }

		Schema::create('hr_paycheck', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('employee_id');
			$table->integer('p_month');
			$table->integer('p_year');
			$table->time('p_month_total_hours');
			$table->time('p_total_worked_hours');
			$table->decimal('p_amount', 10);
			$table->boolean('p_is_recived');
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
		Schema::drop('hr_paycheck');
	}

}
