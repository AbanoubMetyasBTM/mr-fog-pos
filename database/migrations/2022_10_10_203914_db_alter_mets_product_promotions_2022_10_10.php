<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DbAlterMetsProductPromotions20221010 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasColumn('product_promotions', "promo_title")) {
            DB::statement("ALTER TABLE `product_promotions` CHANGE `promo_title` `promo_title` VARCHAR(300) NOT NULL;");
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
