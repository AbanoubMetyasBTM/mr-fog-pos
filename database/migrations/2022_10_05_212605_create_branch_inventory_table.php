<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchInventoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('branch_inventory')) {
            return;
        }

		Schema::create('branch_inventory', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('branch_id');
			$table->integer('inventory_id');
			$table->boolean('is_main_inventory');
		});

	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('branch_inventory');
	}

}
