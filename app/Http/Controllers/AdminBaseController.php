<?php

namespace App\Http\Controllers;

use App\models\notification_m;
use Carbon\Carbon;

class AdminBaseController extends DashboardController
{

    public function __construct()
    {

        parent::__construct();

        $this->getAllLangs();
    }


    public function getAllLangs()
    {

        $all_langs               = parent::getAllLangsForFront();
        $this->data["all_langs"] = $all_langs;
        $all_langs               = collect($all_langs);
        $adminSelectedLang       = getAdminCurrentLangId();

        $this->data["admin_selected_lang_id"]  = $adminSelectedLang;
        $this->data["admin_selected_lang_obj"] = $all_langs->where("lang_id", $adminSelectedLang)->first();
        if($this->data["admin_selected_lang_obj"] == null){
            $this->data["admin_selected_lang_obj"] = $all_langs->first();
        }

    }

    public function getNotificationsHeader()
    {
        $free_cond = " (notifications.user_id = $this->user_id ) ";

        $get_notifications = notification_m::get_notifications([
            "free_conditions" => $free_cond,
            "order_by_col"    => "notifications.not_id",
            "order_by_type"   => "desc",
            "paginate"        => 5
        ]);

        $get_notifications                  = collect($get_notifications->all())->groupBy('notification_date')->all();
        $this->data['notifications_header'] = $get_notifications;
        $this->data['count_notifications']  = notification_m::where('is_seen', 0)->count();
    }


}
