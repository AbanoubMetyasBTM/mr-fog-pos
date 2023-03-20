<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\FrontController;
use App\models\pages_m;
use Illuminate\Http\Request;

class PagesController extends FrontController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function showNotFoundPage(Request $request)
    {
        return $this->returnView($request, "front.subviews.pages.not_found_page");
    }

}
