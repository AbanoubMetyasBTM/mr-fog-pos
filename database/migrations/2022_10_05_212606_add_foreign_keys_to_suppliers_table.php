<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSuppliersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('suppliers', function(Blueprint $table)
		{
			$table->foreign('wallet_id', 'suppliers_ibfk_1')->references('wallet_id')->on('wallets')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('suppliers', function(Blueprint $table)
		{
			$table->dropForeign('suppliers_ibfk_1');
		});
	}

}
