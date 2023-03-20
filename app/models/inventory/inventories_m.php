<?php

namespace App\models\inventory;

use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class inventories_m extends \Eloquent
{
    use SoftDeletes;

    protected $table = "inventory_places";

    protected $primaryKey = "inv_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'inv_name'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            inventory_places.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);

    }

    public static function getAllInventories(): Collection
    {
        return self::getData();
    }

    public static function checkItHasAtLeastOneRow(): bool
    {
        $res = self::limit(1)->first();
        if(is_object($res)){
            return true;
        }
        return false;
    }

}

