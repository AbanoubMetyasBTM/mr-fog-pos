<?php

namespace App\models;

use App\form_builder\BrandsBuilder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class money_to_loyalty_points_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "money_to_loyalty_points";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'money_currency', 'money_amount', 'reward_points'
    ];

    private static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            money_to_loyalty_points.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);

    }

    public static function getAllMoneyToLoyaltyPoints(): Collection
    {
        return self::getData();
    }

    public static function getNearestRowAccordingToPaidMoney($paidMoney, $moneyCurrency)
    {
        $paidMoney = floatval($paidMoney);

        return self::getData([
            "free_conds" => [
                "money_to_loyalty_points.money_amount <= $paidMoney",
                "money_to_loyalty_points.money_currency = '$moneyCurrency'"
            ],
            "order_by" => ["money_amount","desc"]
        ])->first();

    }


}
