<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('inventory_log')) {
            return;
        }

		Schema::create('inventory_log', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('inventory_id');
			$table->integer('pro_id');
			$table->integer('pro_sku_id');
			$table->integer('log_box_quantity');
			$table->integer('log_item_quantity');
			$table->string('log_type', 300)->comment('order, transfer_to_another, broken_products, invalid_entry, add_inventory');
			$table->string('log_operation', 50)->comment('increase or decrease');
			$table->text('log_desc');
			$table->boolean('is_refunded');
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
		Schema::drop('inventory_log');
	}

}
