<?php

namespace App\models\permissions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class permissions_m extends Model
{
    protected $table      = "permissions";
    protected $primaryKey = "per_id";
    public    $timestamps = false;

    protected $fillable = [
        'user_id', 'page_name', 'show_action', 'add_action', 'edit_action', 'delete_action', 'additional_permissions'
    ];


    public static function get_permissions($additional_cond = "")
    {
        $results = DB::select("
            select per.*
            from permissions as per
            $additional_cond
        ");

        return $results;
    }
}
