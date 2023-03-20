<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood2022108AlterMoneyInstallmentsEditColImgObj extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('money_installments', 'img_obj')) {

            DB::statement("
             ALTER TABLE `money_installments` CHANGE `img_obj` `img_obj` VARCHAR(300)
              CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
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
