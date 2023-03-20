<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientLoyalPointsLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('client_loyal_points_log')) {
            return;
        }

		Schema::create('client_loyal_points_log', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('client_id');
			$table->string('log_type', 50)->comment('order_reward, buy_order');
			$table->string('log_operation', 50)->comment('	\'increase\',\'decrease\'');
			$table->decimal('points_amount', 10);
			$table->string('log_desc', 300);
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
		Schema::drop('client_loyal_points_log');
	}

}
