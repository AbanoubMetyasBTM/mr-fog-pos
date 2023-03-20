<?php

namespace App\models;

use Illuminate\Database\Eloquent\SoftDeletes;

class langs_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "langs";

    protected $primaryKey = "lang_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'lang_title', "lang_text", "lang_is_rtl",
        "lang_is_active", "lang_is_default", "lang_img_obj"
    ];


}
