<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoyaltyPointsToMoneyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if (Schema::hasTable('loyalty_points_to_money')) {
            return;
        }

		Schema::create('loyalty_points_to_money', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('money_currency', 20);
			$table->integer('points_amount');
			$table->decimal('reward_money', 10);
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
		Schema::drop('loyalty_points_to_money');
	}

}
