<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSkusTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('product_skus')) {
            return;
        }

		Schema::create('product_skus', function(Blueprint $table)
		{
			$table->integer('ps_id', true);
			$table->integer('pro_id')->index('product_skus_ibfk_1');
			$table->string('ps_box_barcode', 300);
			$table->string('ps_item_barcode', 300);
			$table->decimal('ps_item_retailer_price', 10);
			$table->decimal('ps_item_wholesaler_price', 10);
			$table->decimal('ps_box_retailer_price', 10);
			$table->decimal('ps_box_wholesaler_price', 10);
			$table->text('ps_selected_variant_type_values');
			$table->boolean('is_active');
			$table->string('ps_img_obj', 300);
			$table->text('ps_slider');
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
		Schema::drop('product_skus');
	}

}
