<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221214AlterGiftCards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('gift_cards', 'wallet_paid_amount')) {
            DB::statement("
                ALTER TABLE `gift_cards` ADD `wallet_paid_amount` DECIMAL(10,2) NOT NULL AFTER `card_price`;
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
