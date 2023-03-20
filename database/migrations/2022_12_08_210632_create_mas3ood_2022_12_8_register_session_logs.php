<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMas3ood2022128RegisterSessionLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('register_session_logs')) {
            return;
        }

        Schema::create('register_session_logs', function(Blueprint $table)
        {
            $table->integer('id', true);
            $table->integer('item_id')->comment('item_id => order_id, gift_card_id');
            $table->string('item_type', 300)->comment('order, gift_card');
            $table->string('operation_type', 300)->comment('buy, return');
            $table->decimal('cash_paid_amount', 10)->default(0);
            $table->decimal('debit_card_paid_amount', 10)->default(0);
            $table->decimal('credit_card_paid_amount', 10)->default(0);
            $table->decimal('cheque_paid_amount', 10)->default(0);
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
