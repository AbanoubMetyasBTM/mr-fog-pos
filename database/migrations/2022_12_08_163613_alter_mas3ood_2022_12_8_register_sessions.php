<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022128RegisterSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('register_sessions', 'register_closed_at')) {
            DB::statement("
                ALTER TABLE `register_sessions` CHANGE `register_closed_at` `register_closed_at` DATETIME NULL;
            ");
        }

        if (Schema::hasColumn('register_sessions', 'register_start_money')) {
            DB::statement("
                ALTER TABLE `register_sessions` DROP `register_start_money`;
            ");
        }

        if (Schema::hasColumn('register_sessions', 'register_closed_money')) {
            DB::statement("
                ALTER TABLE `register_sessions` DROP `register_closed_money`;
            ");
        }

        if (!Schema::hasColumn('register_sessions', 'register_start_cash_amount')) {
            DB::statement("
                ALTER TABLE `register_sessions` ADD `register_start_cash_amount` DECIMAL(10,2)
                NOT NULL AFTER `register_start_at`;
            ");
        }

        if (!Schema::hasColumn('register_sessions', 'register_end_cash_amount')) {
            DB::statement("
                ALTER TABLE `register_sessions` ADD `register_end_cash_amount`
                DECIMAL(10,2) NOT NULL AFTER `register_closed_at`;
            ");
        }

        if (!Schema::hasColumn('register_sessions', 'register_end_debit_count')) {
            DB::statement("
                ALTER TABLE `register_sessions` ADD `register_end_debit_count`
                INT(11) NOT NULL AFTER `register_end_cash_amount`;
            ");
        }

        if (!Schema::hasColumn('register_sessions', 'register_end_debit_amount')) {
            DB::statement("
                ALTER TABLE `register_sessions` ADD `register_end_debit_amount`
                DECIMAL(10,2) NOT NULL AFTER `register_end_debit_count`;
            ");
        }

        if (!Schema::hasColumn('register_sessions', 'register_end_credit_count')) {
            DB::statement("
                ALTER TABLE `register_sessions` ADD `register_end_credit_count`
                INT(11) NOT NULL AFTER `register_end_debit_amount`;
            ");
        }

        if (!Schema::hasColumn('register_sessions', 'register_end_credit_amount')) {
            DB::statement("
                ALTER TABLE `register_sessions` ADD `register_end_credit_amount`
                DECIMAL(10,2) NOT NULL AFTER `register_end_credit_count`;
            ");
        }

        if (!Schema::hasColumn('register_sessions', 'register_end_cheque_count')) {
            DB::statement("
                ALTER TABLE `register_sessions` ADD `register_end_cheque_count`
                INT(11) NOT NULL AFTER `register_end_credit_amount`;
            ");
        }

        if (!Schema::hasColumn('register_sessions', 'register_end_cheque_amount')) {
            DB::statement("
                ALTER TABLE `register_sessions` ADD `register_end_cheque_amount`
                DECIMAL(10,2) NOT NULL AFTER `register_end_cheque_count`;
            ");
        }

        if (!Schema::hasColumn('register_sessions', 'approved_by_admin')) {
            DB::statement("
                ALTER TABLE `register_sessions` ADD `approved_by_admin` TINYINT(1)
                NOT NULL AFTER `register_end_cheque_amount`;
            ");
        }

        if (!Schema::hasColumn('register_sessions', 'approved_by_user_id')) {
            DB::statement("
                ALTER TABLE `register_sessions` ADD `approved_by_user_id` INT(11)
                NOT NULL COMMENT 'user => admin branch or admin' AFTER `approved_by_admin`;

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
