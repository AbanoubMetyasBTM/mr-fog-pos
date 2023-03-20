<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeWarningsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('employee_warnings')) {
            return;
        }

		Schema::create('employee_warnings', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('employee_id');
			$table->text('warning_desc');
			$table->string('warning_img_obj', 300);
			$table->boolean('warning_is_received');
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
		Schema::drop('employee_warnings');
	}

}
