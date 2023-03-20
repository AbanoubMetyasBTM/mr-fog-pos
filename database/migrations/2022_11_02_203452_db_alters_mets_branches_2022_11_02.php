<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DbAltersMetsBranches20221102 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasColumn('branches', 'visa_wallet_id')) {

            DB::statement("
                ALTER TABLE `branches` CHANGE `visa_wallet_id` `debit_card_wallet_id` INT(11) NOT NULL;
            ");

            DB::statement("
                ALTER TABLE `branches` ADD `credit_card_wallet_id` INT NOT NULL AFTER `debit_card_wallet_id`, ADD `cheque_wallet_id` INT NOT NULL AFTER `credit_card_wallet_id`;
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
