<?php

namespace App\models\product;

use App\models\HasNoTimeStamp;
use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class product_variant_type_values_m extends \Eloquent
{

    use HasNoTimeStamp;

    protected $table = "product_variant_type_values";

    protected $primaryKey = "vt_value_id";

    protected $fillable = [
        'pro_id', 'variant_type_id', 'vt_value_name'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            product_variant_type_values.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getProductVariantValues(int $proId): Collection
    {
        return self::getData([
            "free_conds" => [
                "product_variant_type_values.pro_id = {$proId}"
            ],
        ]);
    }

    public static function getAllProductVariantTypeValues(): Collection
    {
        return self::getData();
    }


    public static function getProductVariantTypeValuesByIds($ids): Collection
    {
        return self::getData([
            "whereIn" => [
                "vt_value_id" => $ids
            ]
        ]);
    }


}
