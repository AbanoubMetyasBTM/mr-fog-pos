<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $userObj = \DB::table("users")->where("email","admin@admin.com")->get()->first();

        if(!is_object($userObj)){
            \DB::table("users")->insert([
                'user_type'   => "dev",
                'user_role'   => "admin",
                'user_enc_id' => md5("1"),
                'email'       => "admin@admin.com",
                'first_name'  => "admin",
                'last_name'   => "admin",
                'full_name'   => "admin",
                'password'    => bcrypt("123"),
                'is_active'   => 1
            ]);
        }

    }
}
