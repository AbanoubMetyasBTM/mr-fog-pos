<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('permissions')) {
            return;
        }

		Schema::create('permissions', function(Blueprint $table)
		{
			$table->integer('per_id', true);
			$table->integer('user_id')->unsigned()->index('user_id');
			$table->string('page_name', 300);
			$table->boolean('show_action');
			$table->boolean('add_action');
			$table->boolean('edit_action');
			$table->boolean('delete_action');
			$table->text('additional_permissions');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('permissions');
	}

}
