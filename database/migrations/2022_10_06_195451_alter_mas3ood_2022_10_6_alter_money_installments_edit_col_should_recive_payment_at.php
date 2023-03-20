<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022106AlterMoneyInstallmentsEditColShouldRecivePaymentAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (Schema::hasColumn('money_installments', 'should_recive_payment_at')) {

            DB::statement("
              ALTER TABLE `money_installments` CHANGE `should_recive_payment_at`
               `should_receive_payment_at` DATE NOT NULL;
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
