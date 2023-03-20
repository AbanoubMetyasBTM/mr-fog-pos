<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReviewsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('product_reviews')) {
            return;
        }

		Schema::create('product_reviews', function(Blueprint $table)
		{
			$table->integer('review_id', true);
			$table->integer('branch_id');
			$table->integer('client_id');
			$table->integer('product_id');
			$table->text('review_text');
			$table->integer('review_stars_val');
			$table->boolean('review_approved');
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
		Schema::drop('product_reviews');
	}

}
