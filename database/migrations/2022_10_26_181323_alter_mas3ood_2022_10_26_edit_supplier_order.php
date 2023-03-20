<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221026EditSupplierOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('supplier_orders', 'inventory_id')) {

            DB::statement("
                ALTER TABLE `supplier_orders` DROP `inventory_id`;
            ");
        }

        if (!Schema::hasColumn('supplier_orders', 'additional_fees')) {

            DB::statement("
                ALTER TABLE `supplier_orders` ADD `additional_fees` DECIMAL(10,2) NOT NULL AFTER `total_taxes`;
            ");
        }

        if (!Schema::hasColumn('supplier_orders', 'additional_fees_desc')) {

            DB::statement("
                ALTER TABLE `supplier_orders` ADD `additional_fees_desc` VARCHAR(300) NOT NULL AFTER `total_taxes`;
            ");
        }

        if (!Schema::hasColumn('supplier_orders', 'ordered_at')) {

            DB::statement("
              ALTER TABLE `supplier_orders` ADD `ordered_at` DATETIME NULL AFTER `order_status`;
            ");
        }

        if (!Schema::hasColumn('supplier_orders', 'paid_amount')) {

            DB::statement("
                ALTER TABLE `supplier_orders` ADD `paid_amount` DECIMAL(10,2) NOT NULL AFTER `ordered_at`, ADD `remain_amount` DECIMAL(10,2) NOT NULL AFTER `paid_amount`;
            ");
        }

        if (!Schema::hasColumn('supplier_orders', 'original_order_id')) {

            DB::statement("
              ALTER TABLE `supplier_orders` ADD `original_order_id` VARCHAR(50) NOT NULL AFTER `supplier_order_id`;
            ");
        }

        if (!Schema::hasColumn('supplier_orders', 'original_order_img_obj')) {

            DB::statement("
              ALTER TABLE `supplier_orders` ADD `original_order_img_obj` VARCHAR(300) NOT NULL AFTER `original_order_id`;
            ");
        }



        if (Schema::hasColumn('supplier_orders', 'remain_amount')) {

            DB::statement("
              ALTER TABLE `supplier_orders` DROP `remain_amount`;
            ");
        }

        if (Schema::hasColumn('supplier_orders', 'branch_id')) {

            DB::statement("
                ALTER TABLE `supplier_orders` CHANGE `branch_id` `branch_id` INT(11) NULL;
            ");
        }

        if (!Schema::hasColumn('supplier_order_items', 'inventory_id')) {

            DB::statement("
                ALTER TABLE `supplier_order_items` ADD `inventory_id` INT NOT NULL AFTER `supplier_order_id`;
            ");
        }

        if (!Schema::hasColumn('supplier_order_items', 'item_tax')) {

            DB::statement("
               ALTER TABLE `supplier_order_items` ADD `item_tax` DECIMAL(10,2) NOT NULL AFTER `item_cost`;
            ");
        }

        if (!Schema::hasColumn('supplier_order_items', 'item_total_cost')) {

            DB::statement("
                ALTER TABLE `supplier_order_items` ADD `item_total_cost` DECIMAL(10,2) NOT NULL COMMENT 'item_cost + item_tax' AFTER `item_tax`;
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
