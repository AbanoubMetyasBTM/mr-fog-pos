<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221017EmployeeTasksComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('employee_task_comments', 'comment_img')) {

            DB::statement("
                ALTER TABLE `employee_task_comments` ADD `comment_img` VARCHAR(300) NULL AFTER `task_comment`;
            ");
        }

        if (!Schema::hasColumn('employee_task_comments', 'comment_file')) {

            DB::statement("
                ALTER TABLE `employee_task_comments` ADD `comment_file` VARCHAR(300) NULL AFTER `task_comment`;
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
