<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyInstallmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('money_installments')) {
            return;
        }

		Schema::create('money_installments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('wallet_id');
			$table->string('money_type', 50)->comment('owed || dept');
			$table->decimal('money_amount', 10);
			$table->date('should_recive_payment_at');
			$table->boolean('is_received');
			$table->string('img_obj', 300);
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
		Schema::drop('money_installments');
	}

}
