<?php

use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $langObj = \DB::table("langs")->where("lang_title", "en")->get()->first();

        if (!is_object($langObj)) {
            \DB::table("langs")->insert([
                'lang_title'      => "en",
                'lang_text'       => "english",
                'lang_is_active'  => 1,
                'lang_is_default' => 1,
            ]);
        }

    }
}
