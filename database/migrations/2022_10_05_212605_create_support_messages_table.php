<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('support_messages')) {
            return;
        }

		Schema::create('support_messages', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('branch_id');
			$table->integer('client_id')->nullable();
			$table->string('full_name', 300);
			$table->string('phone', 300);
			$table->string('email', 300);
			$table->text('message');
			$table->boolean('is_seen');
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
		Schema::drop('support_messages');
	}

}
