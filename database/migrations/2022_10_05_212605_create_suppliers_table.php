<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('suppliers')) {
            return;
        }

		Schema::create('suppliers', function(Blueprint $table)
		{
			$table->integer('sup_id', true);
			$table->integer('wallet_id')->index('wallet_id');
			$table->string('sup_name', 300);
			$table->string('sup_phone', 300);
			$table->string('sup_company', 300);
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
		Schema::drop('suppliers');
	}

}
