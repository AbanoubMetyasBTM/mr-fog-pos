<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('product_variant_types')) {
            return;
        }

		Schema::create('product_variant_types', function(Blueprint $table)
		{
			$table->integer('variant_type_id', true);
			$table->integer('pro_id')->index('product_variant_types_ibfk_1');
			$table->string('variant_type_name', 300);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_variant_types');
	}

}
