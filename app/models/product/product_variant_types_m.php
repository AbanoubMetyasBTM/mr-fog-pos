<?php

namespace App\models\product;

use App\models\HasNoTimeStamp;
use App\models\ModelUtilities;
use Illuminate\Support\Collection;

class product_variant_types_m extends \Eloquent
{

    use HasNoTimeStamp;

    protected $table = "product_variant_types";

    protected $primaryKey = "variant_type_id";

    protected $fillable = [
        'pro_id', 'variant_type_name',
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            product_variant_types.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getProductVariantTypes(int $proId):Collection
    {
        return self::getData([
            "free_conds" => [
                "product_variant_types.pro_id = {$proId}"
            ],
        ]);
    }



}
