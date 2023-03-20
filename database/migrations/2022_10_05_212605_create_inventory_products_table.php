<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('inventory_products')) {
            return;
        }

		Schema::create('inventory_products', function(Blueprint $table)
		{
			$table->integer('ip_id', true);
			$table->integer('inventory_id');
			$table->integer('pro_id');
			$table->integer('pro_sku_id');
			$table->integer('ip_box_quantity');
			$table->integer('ip_item_quantity');
			$table->integer('total_items_quantity')->comment('عدد الكامل للقطع الموججودة في المخزن');
			$table->integer('quantity_limit')->comment('حد الطلب');
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
		Schema::drop('inventory_products');
	}

}
