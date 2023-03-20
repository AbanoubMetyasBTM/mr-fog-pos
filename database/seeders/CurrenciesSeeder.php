<?php

use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $itemObj = \DB::table("currencies")->where("currency_code", "eur")->get()->first();

        if (!is_object($itemObj)) {
            \DB::table("currencies")->insert([
                'currency_name'      => "eur",
                'currency_code'      => "eur",
                'currency_rate'      => 1,
                'currency_is_active' => 1,
            ]);
        }


    }
}
