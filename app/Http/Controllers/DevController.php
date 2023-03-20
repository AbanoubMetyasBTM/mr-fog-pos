<?php

namespace App\Http\Controllers;

use App\models\langs_m;
use Illuminate\Http\Request;

class DevController extends DashboardController
{

    public $current_user_data;


    public function __construct()
    {
        parent::__construct();
        $this->middleware("check_dev");
        $this->current_user_data = $this->data["current_user"];

        $this->data["all_langs"] = langs_m::all();
    }


    public function returnView(Request $request, $viewPath, $main_dir = "dev")
    {
        return parent::returnView($request, $viewPath, $main_dir);
    }

}
