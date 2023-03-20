<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221011ProductSkusAddColPsSelectedVariantTypeValuesText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('product_skus', 'ps_selected_variant_type_values_text')) {

            DB::statement("
                ALTER TABLE `product_skus` ADD `ps_selected_variant_type_values_text` TEXT NOT NULL
                COMMENT 'selected variant type names' AFTER `ps_selected_variant_type_values`;
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



