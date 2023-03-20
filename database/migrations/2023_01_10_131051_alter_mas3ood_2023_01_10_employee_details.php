<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20230110EmployeeDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('employee_details', 'employee_sick_leave_max_requests')) {
            DB::statement("
                ALTER TABLE `employee_details` CHANGE `employee_sick_leave_max_requests`
                `employee_sick_vacation_max_requests` INT(11) NOT NULL;
            ");
        }

        if (!Schema::hasColumn('clients', 'points_wallet_id')) {
            DB::statement("
               ALTER TABLE clients ADD points_wallet_id INT NOT NULL AFTER wallet_id;
            ");
        }

        if (Schema::hasTable('client_loyal_points_log')) {
            DB::statement("
                DROP TABLE client_loyal_points_log;
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
