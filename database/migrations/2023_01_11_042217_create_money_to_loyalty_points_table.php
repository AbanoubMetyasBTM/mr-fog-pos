<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyToLoyaltyPointsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

        if (Schema::hasTable('money_to_loyalty_points')) {
            return;
        }

		Schema::create('money_to_loyalty_points', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('money_currency', 20);
			$table->decimal('money_amount', 10);
			$table->integer('reward_points');
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
		Schema::drop('money_to_loyalty_points');
	}

}
