<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('coupons')) {
            return;
        }

		Schema::create('coupons', function(Blueprint $table)
		{
			$table->integer('coupon_id', true);
			$table->integer('branch_id');
			$table->text('coupon_title');
			$table->string('coupon_code', 300);
			$table->dateTime('coupon_start_date');
			$table->dateTime('coupon_end_date');
			$table->string('coupon_code_type', 50)->comment('value or percent');
			$table->decimal('coupon_code_value', 10);
			$table->boolean('coupon_is_active');
			$table->integer('coupon_limited_number');
			$table->integer('coupon_used_times');
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
		Schema::drop('coupons');
	}

}
