<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022116ClientOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('client_orders', 'payment_method')) {
            DB::statement("
                ALTER TABLE `client_orders` DROP `payment_method`;
            ");
        }

        if (Schema::hasColumn('client_orders', 'payment_method')) {
            DB::statement("
                ALTER TABLE `client_orders` DROP `payment_method`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'additional_fees_desc')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `additional_fees_desc` VARCHAR(300) NULL AFTER `total_taxes`;
            ");
        }


        if (!Schema::hasColumn('client_orders', 'additional_fees')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `additional_fees` DECIMAL(10,2) NULL AFTER `additional_fees_desc`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'cash_paid_amount')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `cash_paid_amount` DECIMAL(10,2) NULL AFTER `additional_fees`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'debit_card_paid_amount')) {
            DB::statement("
                ALTER TABLE `client_orders` ADD `debit_card_paid_amount` DECIMAL(10,2) NULL AFTER `cash_paid_amount`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'debit_card_receipt_img_obj')) {
            DB::statement("
               ALTER TABLE `client_orders` ADD `debit_card_receipt_img_obj` VARCHAR(300) NULL AFTER `debit_card_paid_amount`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'credit_card_paid_amount')) {
            DB::statement("
               ALTER TABLE `client_orders` ADD `credit_card_paid_amount` DECIMAL(10,2) NULL AFTER `debit_card_receipt_img_obj`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'credit_card_receipt_img_obj')) {
            DB::statement("
              ALTER TABLE `client_orders` ADD `credit_card_receipt_img_obj` VARCHAR(300) NULL AFTER `credit_card_paid_amount`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'cheque_paid_amount')) {
            DB::statement("
              ALTER TABLE `client_orders` ADD `cheque_paid_amount` DECIMAL(10,2) NULL AFTER `credit_card_receipt_img_obj`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'cheque_card_receipt_img_obj')) {
            DB::statement("
              ALTER TABLE `client_orders` ADD `cheque_card_receipt_img_obj` VARCHAR(300) NULL AFTER `cheque_paid_amount`;
            ");
        }

        if (!Schema::hasColumn('client_orders', 'total_paid_amount')) {
            DB::statement("
              ALTER TABLE `client_orders` ADD `total_paid_amount` DECIMAL(10,2) NOT NULL AFTER `total_return_amount`;
            ");
        }

        if (Schema::hasColumn('client_orders', 'used_coupon_id')) {
            DB::statement("
              ALTER TABLE `client_orders` CHANGE `used_coupon_id` `used_coupon_id` INT(11) NULL;
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
