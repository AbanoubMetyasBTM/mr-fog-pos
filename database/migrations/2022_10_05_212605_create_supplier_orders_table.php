<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('supplier_orders')) {
            return;
        }

		Schema::create('supplier_orders', function(Blueprint $table)
		{
			$table->integer('supplier_order_id', true);
			$table->integer('branch_id');
			$table->integer('inventory_id');
			$table->integer('supplier_id');
			$table->integer('employee_id');
			$table->decimal('total_items_cost', 10);
			$table->decimal('total_taxes', 10);
			$table->decimal('total_return_amount', 10);
			$table->decimal('total_cost', 10);
			$table->string('order_status', 250)->comment('pending, done');
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
		Schema::drop('supplier_orders');
	}

}
