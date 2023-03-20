<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class support_messages_m extends Model
{
    use SoftDeletes;

    protected $table    = "support_messages";

    protected $dates    = ["deleted_at"];

    protected $fillable =
    [
        'user_id','msg_type','full_name','phone',
        'email', 'message','is_seen', 'ip_address',
        'country' ,'timezone', 'UDID', 'device_type',
        'device_name','os_version','app_version'
    ];

    static function get_support_messages($attrs = [])
    {

        $results = support_messages_m::select(DB::raw("
            support_messages.*
        "));

        return ModelUtilities::general_attrs($results,$attrs);

    }

}
