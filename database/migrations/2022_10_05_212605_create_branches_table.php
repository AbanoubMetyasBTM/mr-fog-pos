<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('branches')) {
            return;
        }

		Schema::create('branches', function(Blueprint $table)
		{
			$table->integer('branch_id', true);
			$table->integer('cash_wallet_id');
			$table->integer('visa_wallet_id');
			$table->string('branch_api_access_token', 300);
			$table->string('branch_name', 300);
			$table->string('branch_country', 300);
			$table->string('branch_currency', 300)->comment('USD, CAD');
			$table->text('branch_taxes');
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
		Schema::drop('branches');
	}

}
