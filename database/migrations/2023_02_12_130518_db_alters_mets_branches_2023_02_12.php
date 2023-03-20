<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DbAltersMetsBranches20230212 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('branches', 'return_policy_days')) {
            DB::statement("ALTER TABLE `branches` ADD `return_policy_days` INT NOT NULL AFTER `first_day_of_the_week`;");
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
