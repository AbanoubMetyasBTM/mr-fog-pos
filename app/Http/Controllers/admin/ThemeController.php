<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AdminBaseController;
use App\models\attachments_m;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Auth;

class ThemeController extends AdminBaseController
{

    public function __construct()
    {

        parent::__construct();

    }

    public function changeDirectionController($locale)
    {

        $locale = clean($locale);

        session([
            'locale' => $locale
        ]);

        return redirect()->back();

    }

    public function changeMenuController($menu_display)
    {

        $menu_display = clean($menu_display);

        session([
            'menu_display' => $menu_display
        ]);

        return redirect()->back();

    }

    public function DarkModeController(Request $request)
    {

        $dark_mode = $request->get('dark_mode','off');
        $dark_mode = clean($dark_mode);

        session([
            'dark_mode' => $dark_mode
        ]);

    }

}
