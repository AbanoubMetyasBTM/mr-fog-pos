<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AdminBaseController;
use App\models\notification_m;
use Illuminate\Http\Request;

class NotificationController extends AdminBaseController
{

    public function __construct()
    {

        parent::__construct();

    }

    public function index(Request $request,$not_type)
    {
        $cond       = [];
        $cond[]     = ["notifications.user_id","=",$this->user_id];


        $get_notifications = notification_m::get_notifications([
            'additional_and_wheres' => $cond,
            'order_by_col'          => "notifications.not_id",
            'order_by_type'         => "desc",
            'paginate'              => 20
        ]);
        $this->data['results'] = $get_notifications;
        $get_all_notifications = collect($get_notifications->all())->groupBy('notification_date')->all();
        $this->data['all_notifications'] = $get_all_notifications;



        /**
         * count notification by notification type
         */
        $this->data['total_notifications']                  = 0;
        $get_not_count = notification_m::get_notifications_count();

        $get_not_count = collect($get_not_count)->groupBy('not_type')->all();

        if(isset($get_not_count['request_link']))
        {
            $this->data['total_notifications']              += $this->data['count_request_link_notifications'];
        }

        return $this->returnView($request,"admin.subviews.notifications.show");
    }

    public function seen_notifications(Request $request)
    {
        $notifications = notification_m::where('is_seen', '=', 0)->update(array('is_seen' => 1));
        return $notifications;

    }

    public function delete(Request $request){

        $this->general_remove_item($request,'App\models\notification_m');
    }


}
