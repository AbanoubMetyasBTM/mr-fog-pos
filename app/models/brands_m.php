<?php

namespace App\models;

use App\form_builder\BrandsBuilder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class brands_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "brands";

    protected $primaryKey = "brand_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'brand_img_obj', 'brand_name'
    ];

    private static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            brands.*
        "));

        $results = ModelUtilities::getTranslateData($results, "brands", new BrandsBuilder());

        return ModelUtilities::general_attrs($results, $attrs);

    }

    public static function getAllBrands(): Collection
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
