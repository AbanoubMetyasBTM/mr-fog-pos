<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('currencies')) {
            return;
        }

		Schema::create('currencies', function(Blueprint $table)
		{
			$table->integer('currency_id', true);
			$table->string('currency_img_obj', 300);
			$table->string('currency_name', 300);
			$table->string('currency_code', 50);
			$table->decimal('currency_rate', 10);
			$table->boolean('currency_is_active');
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
		Schema::drop('currencies');
	}

}
