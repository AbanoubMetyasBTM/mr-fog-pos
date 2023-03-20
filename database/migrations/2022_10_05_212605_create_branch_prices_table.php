<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchPricesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('branch_prices')) {
            return;
        }

		Schema::create('branch_prices', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('branch_id');
			$table->integer('pro_id');
			$table->integer('sku_id');
			$table->decimal('online_item_price', 10);
			$table->decimal('online_box_price', 10);
			$table->decimal('item_retailer_price', 10);
			$table->decimal('item_wholesaler_price', 10);
			$table->decimal('box_retailer_price', 10);
			$table->decimal('box_wholesaler_price', 10);
			$table->boolean('is_active');
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
		Schema::drop('branch_prices');
	}

}
