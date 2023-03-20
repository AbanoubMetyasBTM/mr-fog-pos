<?php

namespace App\models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class loyalty_points_to_money_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "loyalty_points_to_money";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'money_currency', 'points_amount', 'reward_money'
    ];

    private static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            loyalty_points_to_money.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);

    }

    public static function getAllRows(): Collection
    {
        return self::getData();
    }

    public static function getByCurrency(string $currency): Collection
    {
        return self::getData([
            "free_conds" => [
                "money_currency = '${currency}'",
            ],
        ]);
    }


}
