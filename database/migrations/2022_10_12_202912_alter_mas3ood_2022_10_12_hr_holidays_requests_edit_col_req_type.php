<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221012HrHolidaysRequestsEditColReqType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('hr_holiday_requests', 'req_type')) {

            DB::statement("
             ALTER TABLE `hr_holiday_requests` CHANGE `req_type` `req_type` 
             VARCHAR(300) NOT NULL COMMENT 'vacation, sick_holiday';
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
