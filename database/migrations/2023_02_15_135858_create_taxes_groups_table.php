<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxesGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    Schema::dropIfExists("states");

        if (Schema::hasTable('taxes_groups')) {
            return;
        }

		Schema::create('taxes_groups', function(Blueprint $table)
		{
			$table->increments('group_id');
			$table->string('group_name', 300);
			$table->text('group_taxes');
			$table->timestamps();
			$table->softDeletes();
		});

        if (!Schema::hasColumn('clients', 'tax_group_id')) {
            DB::statement("
                ALTER TABLE `clients` ADD `tax_group_id` integer NULL AFTER `points_wallet_id`;
            ");
        }

        if (!Schema::hasColumn('branches', 'tax_group_id')) {
            DB::statement("
                ALTER TABLE `branches` ADD `tax_group_id` integer NULL AFTER `cheque_wallet_id`;
            ");
        }


	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('taxes_groups');
	}

}
