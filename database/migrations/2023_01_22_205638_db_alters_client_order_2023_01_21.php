<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DbAltersClientOrder20230121 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasColumn('client_orders', 'used_points_redeem_points')) {
            DB::statement("
                ALTER TABLE `client_orders`
                    ADD `used_points_redeem_points` INT NOT NULL AFTER `used_coupon_value`,
                    ADD `used_points_redeem_money` DECIMAL(10,2) NOT NULL AFTER `used_points_redeem_points`;
            ");

            DB::statement("
                ALTER TABLE `client_orders` ADD `can_not_return_items` BOOLEAN NOT NULL AFTER `used_points_redeem_money`;
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
