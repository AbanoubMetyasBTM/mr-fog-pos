<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrNationalHolidaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('hr_national_holidays')) {
            return;
        }

		Schema::create('hr_national_holidays', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('country_name', 300);
			$table->string('holiday_title', 300);
			$table->date('holyday_date');
			$table->date('holyday_year');
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
		Schema::drop('hr_national_holidays');
	}

}
