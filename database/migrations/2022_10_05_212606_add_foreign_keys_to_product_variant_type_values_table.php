<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductVariantTypeValuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('product_variant_type_values', function(Blueprint $table)
		{
			$table->foreign('pro_id', 'product_variant_type_values_ibfk_1')->references('pro_id')->on('products')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('variant_type_id', 'product_variant_type_values_ibfk_2')->references('variant_type_id')->on('product_variant_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('product_variant_type_values', function(Blueprint $table)
		{
			$table->dropForeign('product_variant_type_values_ibfk_1');
			$table->dropForeign('product_variant_type_values_ibfk_2');
		});
	}

}
