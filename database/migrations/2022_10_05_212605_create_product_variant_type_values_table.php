<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantTypeValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('product_variant_type_values')) {
            return;
        }

		Schema::create('product_variant_type_values', function(Blueprint $table)
		{
			$table->integer('vt_value_id', true);
			$table->integer('pro_id')->index('pro_id');
			$table->integer('variant_type_id')->index('variant_type_id');
			$table->string('vt_value_name', 300);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_variant_type_values');
	}

}
