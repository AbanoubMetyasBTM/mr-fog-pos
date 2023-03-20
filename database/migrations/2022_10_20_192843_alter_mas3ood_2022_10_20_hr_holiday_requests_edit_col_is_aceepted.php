<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221020HrHolidayRequestsEditColIsAceepted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('hr_holiday_requests', 'req_is_accepted')) {

            DB::statement("
                ALTER TABLE `hr_holiday_requests` CHANGE `req_is_accepted` `req_status` VARCHAR(50) NOT NULL COMMENT 'pending, accepted, rejected';
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
