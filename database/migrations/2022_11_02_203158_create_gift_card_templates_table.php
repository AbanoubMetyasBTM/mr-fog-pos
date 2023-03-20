<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftCardTemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('gift_card_templates')) {
            return;
        }

		Schema::create('gift_card_templates', function(Blueprint $table)
		{
			$table->integer('template_id', true);
			$table->string('template_title', 300);
			$table->string('template_bg_img_obj', 500);
			$table->text('template_text_positions');
			$table->string('template_text_color', 50);
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
