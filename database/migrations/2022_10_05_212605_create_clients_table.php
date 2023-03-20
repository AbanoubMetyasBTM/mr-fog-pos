<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('clients')) {
            return;
        }

		Schema::create('clients', function(Blueprint $table)
		{
			$table->integer('client_id', true);
			$table->integer('user_id')->nullable();
			$table->integer('wallet_id')->index('wallet_id');
			$table->string('client_type', 50)->comment('retailer, wholesaler');
			$table->text('client_name');
			$table->string('client_email', 300);
			$table->string('client_phone', 300);
			$table->integer('client_total_orders_count');
			$table->decimal('client_total_orders_amount', 10);
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
		Schema::drop('clients');
	}

}
