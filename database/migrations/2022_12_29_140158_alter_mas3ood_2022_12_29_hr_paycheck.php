<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMas3ood20221229HrPaycheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('hr_paycheck')) {
            DB::statement("
                DROP TABLE `hr_paycheck`
            ");
        }

        Schema::create('hr_paycheck', function(Blueprint $table)
        {
            $table->integer('id', true);
            $table->integer('employee_id');
            $table->integer('p_year');
            $table->integer('p_month');
            $table->string('p_weeks', 50)->comment('ex => 1,2,3,4');
            $table->time('p_should_work_hours');
            $table->time('p_total_worked_hours');
            $table->decimal('p_amount', 10);
            $table->boolean('p_is_received');
            $table->timestamps();
            $table->softDeletes();
        });
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
