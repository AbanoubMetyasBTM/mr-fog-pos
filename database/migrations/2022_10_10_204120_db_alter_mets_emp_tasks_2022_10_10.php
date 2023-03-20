<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DbAlterMetsEmpTasks20221010 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasColumn('employee_tasks', "task_slider")) {
            DB::statement("ALTER TABLE `employee_tasks` ADD `task_slider` TEXT NOT NULL AFTER `task_status`;");
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
