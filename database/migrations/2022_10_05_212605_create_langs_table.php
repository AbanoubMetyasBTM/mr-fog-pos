<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLangsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('langs')) {
            return;
        }

		Schema::create('langs', function(Blueprint $table)
		{
			$table->integer('lang_id', true);
			$table->string('lang_title', 20);
			$table->string('lang_text', 100);
			$table->boolean('lang_is_rtl');
			$table->boolean('lang_is_active');
			$table->boolean('lang_is_default');
			$table->integer('lang_img_obj');
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
		Schema::drop('langs');
	}

}
