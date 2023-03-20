<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221215AlterClinetOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('client_orders', 'total_taxes')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `total_taxes` TEXT NOT NULL AFTER `total_paid_amount`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'order_timezone')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `order_timezone` VARCHAR(100) NOT NULL AFTER `order_type`;
            ");
        }

        if (!Schema::hasColumn('gift_cards', 'gift_card_timezone')) {
            DB::statement("
                ALTER TABLE `gift_cards` ADD `gift_card_timezone` VARCHAR(100) NOT NULL AFTER `cheque_card_receipt_img_obj`;
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
