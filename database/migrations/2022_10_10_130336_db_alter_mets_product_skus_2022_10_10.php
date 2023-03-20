<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DbAlterMetsProductSkus20221010 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasColumn('product_skus', "use_default_images")) {
            DB::statement("ALTER TABLE `product_skus` ADD `use_default_images` BOOLEAN NOT NULL AFTER `is_active`;");
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
