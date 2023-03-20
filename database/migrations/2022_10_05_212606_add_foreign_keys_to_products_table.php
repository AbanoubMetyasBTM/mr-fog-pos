<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('products', function(Blueprint $table)
		{
			$table->foreign('brand_id', 'products_ibfk_1')->references('brand_id')->on('brands')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('cat_id', 'products_ibfk_2')->references('cat_id')->on('categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('products', function(Blueprint $table)
		{
			$table->dropForeign('products_ibfk_1');
			$table->dropForeign('products_ibfk_2');
		});
	}

}
