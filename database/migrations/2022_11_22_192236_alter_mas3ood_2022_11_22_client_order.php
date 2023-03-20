<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221122ClientOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasColumn('client_orders', 'gift_card_value')) {
            DB::statement("
                ALTER TABLE `client_orders` CHANGE `gift_card_value` `gift_card_paid_amount`
                DECIMAL(10,2) NULL DEFAULT NULL;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'wallet_return_amount')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `wallet_return_amount` DECIMAL(10,2) NULL AFTER `used_coupon_value`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'gift_card_return_amount')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `gift_card_return_amount` DECIMAL(10,2) NULL AFTER `wallet_return_amount`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'cash_return_amount')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `cash_return_amount` DECIMAL(10,2) NULL AFTER `gift_card_return_amount`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'debit_card_return_amount')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `debit_card_return_amount` DECIMAL(10,2) NULL AFTER `cash_return_amount`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'credit_card_return_amount')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `credit_card_return_amount` DECIMAL(10,2) NULL AFTER `debit_card_return_amount`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'cheque_return_amount')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `cheque_return_amount` DECIMAL(10,2) NULL AFTER `credit_card_return_amount`;
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
