<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221013EmployeeDetailsEditCols extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('employee_details', 'employee_shoud_start_work_at')) {

            DB::statement("
                ALTER TABLE `employee_details` CHANGE `employee_shoud_start_work_at`
                 `employee_should_start_work_at` TIME NOT NULL;
            ");
        }

        if (Schema::hasColumn('employee_details', 'employee_shoud_end_work_at')) {

            DB::statement("
                ALTER TABLE `employee_details` CHANGE `employee_shoud_end_work_at`
                 `employee_should_end_work_at` TIME NOT NULL;
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
