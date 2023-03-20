<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AdminBaseController;
use App\models\branch\branches_m;
use App\models\client\clients_m;
use App\models\gift_card\gift_cards_m;
use App\models\register\registers_m;
use App\models\register\registers_sessions_logs_m;
use App\models\register\registers_sessions_m;
use App\User;
use Illuminate\Http\Request;

class RegisterSessionLogController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function showRegisterSessionLogsOfRegister(Request $request)
    {
        havePermissionOrRedirect("admin/registers_sessions_logs", "show_action");



        $this->data["request_data"]  = (object)$request->all();
        $conds                       = $request->all();
        $conds['paginate']           = 50;
        $this->data["results"]       = registers_sessions_logs_m::getAllRegisterSessionLogs($conds);
        return $this->returnView($request, "admin.subviews.register_sessions.show_register_logs");

    }


}
