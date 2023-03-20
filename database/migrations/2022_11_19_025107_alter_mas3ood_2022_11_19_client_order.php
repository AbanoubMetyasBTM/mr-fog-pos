<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221119ClientOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('client_orders', 'wallet_paid_amount')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `wallet_paid_amount` DECIMAL(10,2) NULL AFTER `total_items_cost`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'gift_card_id')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `gift_card_id` INT NULL AFTER `wallet_paid_amount`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'gift_card_value')) {
            DB::statement("
               ALTER TABLE `client_orders` ADD `gift_card_value` DECIMAL(10,2) NULL AFTER `gift_card_id`;
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
