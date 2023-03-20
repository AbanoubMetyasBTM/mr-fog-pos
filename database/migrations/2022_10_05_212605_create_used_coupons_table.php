<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsedCouponsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('used_coupons')) {
            return;
        }

		Schema::create('used_coupons', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('coupon_id');
			$table->integer('branch_id');
			$table->integer('client_id');
			$table->integer('order_id');
			$table->decimal('discount_value', 10);
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
		Schema::drop('used_coupons');
	}

}
