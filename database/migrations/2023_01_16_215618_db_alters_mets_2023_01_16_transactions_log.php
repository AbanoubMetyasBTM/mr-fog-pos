<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DbAltersMets20230116TransactionsLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("ALTER TABLE `transactions_log` CHANGE `money_type` `money_type` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'cash, visa';");
        DB::statement("ALTER TABLE `transactions_log` CHANGE `transaction_type` `transaction_type` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");

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
