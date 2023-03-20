<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('client_orders')) {
            return;
        }

		Schema::create('client_orders', function(Blueprint $table)
		{
			$table->integer('client_order_id', true);
			$table->integer('branch_id');
			$table->integer('client_id');
			$table->integer('employee_id');
			$table->integer('register_id');
			$table->string('payment_method', 50)->comment('cash, wallet, online_payment');
			$table->decimal('total_items_cost', 10);
			$table->decimal('total_taxes', 10);
			$table->integer('used_coupon_id');
			$table->decimal('used_coupon_value', 10);
			$table->decimal('total_discount', 10);
			$table->decimal('total_return_amount', 10);
			$table->integer('total_cost');
			$table->string('order_status', 300)->comment('pending, accepted, done, rejected, cancelled');
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
		Schema::drop('client_orders');
	}

}
