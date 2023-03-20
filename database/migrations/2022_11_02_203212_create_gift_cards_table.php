<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('gift_cards')) {
            return;
        }

		Schema::create('gift_cards', function(Blueprint $table)
		{
			$table->integer('card_id', true);
			$table->integer('card_template_id');
			$table->integer('wallet_id');
			$table->integer('branch_id');
			$table->integer('employee_id');
			$table->integer('register_id');
			$table->integer('register_session_id');
			$table->integer('client_id');
			$table->string('card_title', 300);
			$table->string('card_unique_number', 50);
			$table->date('card_expiration_date');
			$table->decimal('card_price', 10);
			$table->decimal('cash_paid_amount', 10)->nullable();
			$table->decimal('debit_card_paid_amount', 10)->nullable();
			$table->string('debit_card_receipt_img_obj', 300)->nullable();
			$table->decimal('credit_card_paid_amount', 10)->nullable();
			$table->string('credit_card_receipt_img_obj', 300)->nullable();
			$table->decimal('cheque_paid_amount', 10)->nullable();
			$table->string('cheque_card_receipt_img_obj', 300)->nullable();
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

	}

}
