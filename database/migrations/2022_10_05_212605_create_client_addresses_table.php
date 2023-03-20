<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientAddressesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('client_addresses')) {
            return;
        }

		Schema::create('client_addresses', function(Blueprint $table)
		{
			$table->integer('add_id', true);
			$table->integer('client_id');
			$table->string('add_email', 300);
			$table->string('add_country', 300);
			$table->string('add_city', 300);
			$table->string('add_street', 300);
			$table->string('add_type', 300)->comment('home or work');
			$table->string('add_tel_country_code', 300);
			$table->string('add_tel_number', 300);
			$table->string('add_post_code', 300);
			$table->string('add_notes', 300);
			$table->string('add_lat', 300);
			$table->string('add_lng', 300);
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
		Schema::drop('client_addresses');
	}

}
