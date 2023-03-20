<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMas3ood20221223AddNewEmployeeLoginLogoutTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('employee_login_logout')) {
            DB::statement("
                DROP TABLE `employee_login_logout`
            ");
        }

        Schema::create('employee_login_logout', function(Blueprint $table)
        {
            $table->integer('id', true);
            $table->integer('employee_id');
            $table->date('work_date');
            $table->text('login_logout_times');
            $table->time('late_time_hours');
            $table->time('early_leave_hours');
            $table->time('should_work_hours');
            $table->time('working_hours');
            $table->time('remain_hours');
            $table->time('overtime_hours');
            $table->boolean('is_work_day')->comment('علشان نشوف ده يوم عمل ليه وﻻ اجازة');
            $table->boolean('work_day_is_general_holiday');
            $table->boolean('work_day_is_demanded_holiday')->comment('يوم تم طلبه اجازة');
            $table->boolean('work_day_has_early_leave');
            $table->boolean('work_day_has_delay_request');
            $table->timestamps();
            $table->softDeletes();
        });


        if (!Schema::hasColumn('branches', 'first_day_of_the_week')) {
            DB::statement("
                ALTER TABLE `branches` ADD `first_day_of_the_week` VARCHAR(20)
                NOT NULL COMMENT 'اول يوم في اسبوع العمل' AFTER `branch_taxes`;
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

    }
}
