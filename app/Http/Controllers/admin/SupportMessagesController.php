<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AdminBaseController;
use App\models\support_messages_m;
use Illuminate\Http\Request;

class SupportMessagesController extends AdminBaseController
{

    public function __construct()
    {

        parent::__construct();

        $this->setMetaTitle("Support Messages");

    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/support_messages", "show_action");


        $this->data["request_data"] = (object)$request->all();

        $cond = [];
        $from = clean($request->get('from'));
        $to   = clean($request->get('to'));
        if (isset($from) && !empty($from)) {
            $from = date("Y-m-d", strtotime($from));
            $cond[] = "date(support_messages.created_at) >= '$from'";
        }

        if (isset($to) && !empty($to)) {
            $to = date("Y-m-d", strtotime($to));
            $cond[] = "date(support_messages.created_at) <= '$to'";
        }

        $this->data["results"] = support_messages_m::get_support_messages([
            'free_conds'    => $cond,
            'order_by_col'  => "support_messages.id",
            'order_by_type' => "desc",
            'paginate'      => 20
        ]);

        return $this->returnView($request, 'admin.subviews.support_messages.show');
    }

    public function seen_support_messages(Request $request)
    {
        $message          = support_messages_m::where('id', $request->get('id'))->first();
        $message->is_seen = 1;
        $message->save();
        return $message;
    }

    public function delete(Request $request)
    {

        havePermissionOrRedirect("admin/support_messages", "delete_action");

        $this->general_remove_item($request, 'App\models\support_messages_m');

    }

}
