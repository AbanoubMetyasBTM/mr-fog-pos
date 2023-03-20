<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class settings_m extends Model
{

    use SoftDeletes;

    protected $table        = "settings";
    protected $primaryKey   = "settings_id";

    protected $fillable     =
    [
        'setting_group', 'setting_key', 'setting_type', 'setting_value'
    ];


    static function get_settings($attrs = [])
    {

        $results = settings_m::select(DB::raw("
            settings.*
        "));

        return ModelUtilities::general_attrs($results,$attrs);

    }

}
