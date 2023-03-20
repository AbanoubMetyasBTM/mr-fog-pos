<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221117ClientOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('client_orders', 'employee_id')) {
            DB::statement("
                ALTER TABLE `client_orders` CHANGE `employee_id` `employee_id` INT(11) NULL;
            ");
        }

        if (Schema::hasColumn('client_orders', 'register_id')) {
            DB::statement("
                ALTER TABLE `client_orders` CHANGE `register_id` `register_id` INT(11) NULL;
            ");
        }

        if (Schema::hasColumn('client_orders', 'total_discount')) {
            DB::statement("
                ALTER TABLE `client_orders` DROP `total_discount`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'client_user_id')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `client_user_id` INT NULL COMMENT 'store in case order online'
                AFTER `client_id`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'register_session_id')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `register_session_id` INT NULL AFTER `register_id`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'order_type')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `order_type` VARCHAR(50) NOT NULL COMMENT 'online, offline'
                AFTER `order_status`;
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
