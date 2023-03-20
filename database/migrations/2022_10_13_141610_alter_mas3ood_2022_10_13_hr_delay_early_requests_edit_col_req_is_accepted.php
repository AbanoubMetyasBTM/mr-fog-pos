<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221013HrDelayEarlyRequestsEditColReqIsAccepted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('hr_delay_early_requests', 'req_is_accepted')) {

            DB::statement("
                 ALTER TABLE `hr_delay_early_requests` CHANGE `req_is_accepted` `req_is_accepted`
                  TINYINT(1) NULL COMMENT 'null => waiting, 0 => reject, 1 => accept ';
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
