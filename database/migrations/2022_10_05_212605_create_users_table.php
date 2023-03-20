<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('users')) {
            return;
        }

		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('user_id');
			$table->integer('branch_id')->nullable();
			$table->string('user_type', 50)->comment('admin, dev, employee, client');
			$table->string('user_enc_id', 200);
			$table->string('logo_img_obj', 300);
			$table->string('email');
			$table->string('temp_email', 300);
			$table->string('first_name', 300);
			$table->string('last_name', 300);
			$table->string('full_name', 300);
			$table->string('password');
			$table->dateTime('password_changed_at')->nullable();
			$table->string('remember_token', 100)->nullable();
			$table->string('phone', 300);
			$table->string('phone_code', 10);
			$table->string('verification_code', 50);
			$table->dateTime('verification_code_expiration')->nullable();
			$table->string('password_reset_code', 50);
			$table->dateTime('password_reset_expire_at')->nullable();
			$table->boolean('is_active');
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
		Schema::drop('users');
	}

}
