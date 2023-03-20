<?php

namespace App\models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class notification_m extends Model
{

    protected $table        = "notifications";
    protected $primaryKey   = "not_id";

    protected $fillable     =
    [
        'user_id','not_type','target_id',
        'not_title','is_seen'
    ];

    static function get_notifications($attrs=[])
    {

        $results = self::select(DB::raw("
            notifications.*,
            date(created_at) as notification_date

        "));

        return ModelUtilities::general_attrs($results,$attrs);

    }

    static function get_notifications_count()
    {

        $notifications = DB::select("SELECT not_type, count(*) as count_notifications
                    FROM `notifications`
                    GROUP BY not_type");
        return $notifications;

    }

}
