<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierOrderItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('supplier_order_items')) {
            return;
        }

		Schema::create('supplier_order_items', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('operation_type', 50)->comment('buy or return');
			$table->integer('supplier_order_id');
			$table->integer('pro_id');
			$table->integer('pro_sku_id');
			$table->string('item_type', 50)->comment('box or item');
			$table->integer('order_quantity');
			$table->decimal('item_cost', 10);
			$table->decimal('total_items_cost', 10);
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
		Schema::drop('supplier_order_items');
	}

}
