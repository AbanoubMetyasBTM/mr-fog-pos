<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DbAlterMetsEmpDetails20221010 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasColumn('employee_details', "employee_early_requests_max_requests")) {
            DB::statement("ALTER TABLE `employee_details` ADD `employee_early_requests_max_requests` INT NOT NULL AFTER `employee_delay_requests_max_requests`;");
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
