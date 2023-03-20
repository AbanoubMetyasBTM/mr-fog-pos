<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221012HrNationalHolidaysEditColsNames extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('hr_national_holidays', 'holyday_date')) {

            DB::statement("
                ALTER TABLE `hr_national_holidays` CHANGE `holyday_date` `holiday_date` DATE NOT NULL;
            ");
        }

        if (Schema::hasColumn('hr_national_holidays', 'holyday_year')) {

            DB::statement("
                ALTER TABLE `hr_national_holidays` CHANGE `holyday_year` `holiday_year` DATE NOT NULL;
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
