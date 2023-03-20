<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221114ClientOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('client_orders', 'additional_fees_desc')) {
            DB::statement("
              ALTER TABLE `client_orders` DROP `additional_fees_desc`;
            ");
        }

        if (Schema::hasColumn('client_orders', 'additional_fees')) {
            DB::statement("
              ALTER TABLE `client_orders` DROP `additional_fees`;
            ");
        }

        if (Schema::hasColumn('client_orders', 'total_taxes')) {
            DB::statement("
              ALTER TABLE `client_orders` DROP `total_taxes`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'pick_up_date')) {
            DB::statement("
              ALTER TABLE `client_orders` ADD `pick_up_date` DATE NULL AFTER `order_status`;
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
