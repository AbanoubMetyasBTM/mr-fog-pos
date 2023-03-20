<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DbAltersMetsGiftCardTemplates20230312 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasColumn('gift_card_templates', 'is_active')) {
            DB::statement("ALTER TABLE `gift_card_templates` ADD `is_active` BOOLEAN NOT NULL AFTER `template_text_color`;");
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
