<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPromotionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

	    if (Schema::hasTable('product_promotions')) {
            return;
        }

		Schema::create('product_promotions', function(Blueprint $table)
		{
			$table->integer('promo_id', true);
			$table->integer('promo_branch_id')->nullable()->comment('if null then it will apply at all branches');
			$table->integer('promo_title');
			$table->dateTime('promo_start_at');
			$table->dateTime('promo_end_at');
			$table->text('promo_sku_ids')->nullable()->comment('if null or empty, it will apply at all products');
			$table->integer('promo_discount_percent');
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
		Schema::drop('product_promotions');
	}

}
