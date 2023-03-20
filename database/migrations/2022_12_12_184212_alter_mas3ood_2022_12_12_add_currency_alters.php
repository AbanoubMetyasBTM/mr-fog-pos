<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221212AddCurrencyAlters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasColumn('transactions_log', 'transaction_currency')) {
            DB::statement("
               ALTER TABLE `transactions_log` ADD `transaction_currency` VARCHAR(20) NOT NULL AFTER `transaction_operation`;
            ");
        }

        if (!Schema::hasColumn('suppliers', 'sup_currency')) {
            DB::statement("
                ALTER TABLE `suppliers` ADD `sup_currency` VARCHAR(20) NOT NULL AFTER `sup_company`;
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
