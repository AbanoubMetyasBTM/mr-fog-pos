<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('settings')) {
            return;
        }

		Schema::create('settings', function(Blueprint $table)
		{
			$table->increments('settings_id');
			$table->string('setting_group')->index()->comment('app or system or push_notification');
			$table->string('setting_key')->index()->comment('type or version or mail_type');
			$table->string('setting_type')->comment('text or image');
			$table->string('setting_value');
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
		Schema::drop('settings');
	}

}
