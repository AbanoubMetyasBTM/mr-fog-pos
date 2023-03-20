<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('products')) {
            return;
        }

		Schema::create('products', function(Blueprint $table)
		{
			$table->integer('pro_id', true);
			$table->integer('cat_id')->index('cat_id');
			$table->integer('brand_id')->nullable()->index('brand_id');
			$table->text('pro_name');
			$table->string('pro_img_obj', 300);
			$table->text('pro_slider');
			$table->text('pro_desc');
			$table->integer('standard_box_quantity');
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
		Schema::drop('products');
	}

}
