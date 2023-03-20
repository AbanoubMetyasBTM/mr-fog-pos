<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsByNobelOnProductSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('product_skus', 'ps_box_bought_price')) {

            DB::statement("
            ALTER TABLE `product_skus` ADD `ps_box_bought_price` DECIMAL(10,2) NOT NULL AFTER `ps_item_barcode`;
            ");
        }

        if (!Schema::hasColumn('product_skus', 'ps_item_bought_price')) {

            DB::statement("
            ALTER TABLE `product_skus` ADD `ps_item_bought_price` DECIMAL(10,2) NOT NULL AFTER `ps_box_bought_price`;
            ");
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
