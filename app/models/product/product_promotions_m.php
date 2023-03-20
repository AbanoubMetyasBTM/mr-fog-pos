<?php

namespace App\models\product;

use App\form_builder\ProductPromotionsBuilder;
use App\models\ModelUtilities;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class product_promotions_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "product_promotions";

    protected $primaryKey = "promo_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'promo_branch_id', 'promo_title', 'promo_start_at',
        'promo_end_at', 'promo_sku_ids', 'promo_discount_percent'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            product_promotions.*,
       "));

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getAllProductsPromotions(array $attrs = []): Collection
    {
        $results = self::select(\DB::raw("
            product_promotions.*,
            branches.branch_name,
            " . JsF("product_promotions.promo_title", "promo_title") . "
        "));

        $results = ModelUtilities::getTranslateData($results, "product_promotions", new ProductPromotionsBuilder());
        $results = $results->leftJoin('branches', 'branches.branch_id', '=', 'product_promotions.promo_branch_id');


        return ModelUtilities::general_attrs($results, self::getAllProductsPromotionsConds($attrs));
    }


    private static function getAllProductsPromotionsConds(array $attr = []): array
    {

        $modelUtilitiesAttrs               = [];
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];

        // filters
        if (isset($attr["available_promotions"]) && $attr["available_promotions"] == "yes") {

            $currentTime = Carbon::now();


            $modelUtilitiesAttrs["free_conds"][] = "
               promo_start_at <= '{$currentTime}'
            ";

            $modelUtilitiesAttrs["free_conds"][] = "
               promo_end_at >= '{$currentTime}'
            ";
        }


        return $modelUtilitiesAttrs;

    }

    public static function getPromotionForSpecificBranchAndAllBranches($branchId)
    {
        $currentTime = now();

        return self::select('product_promotions.*')->
            whereRaw(\DB::raw("(promo_branch_id is null or promo_branch_id={$branchId})"))->
            where('promo_start_at','<', $currentTime)->
            where('promo_end_at','>', $currentTime)->
            get();

    }



}
