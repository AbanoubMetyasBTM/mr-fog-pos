<?php

namespace App\models;

use App\form_builder\CategoriesBuilder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class currencies_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "currencies";

    protected $primaryKey = "currency_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'currency_img_obj',
        'currency_name', 'currency_code', 'currency_rate', 'currency_is_active'
    ];

    private static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            currencies.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);

    }

    public static function getAllCurrencies(): Collection
    {

        return self::getData();

    }


    public static function getAllActiveCurrencies(): Collection
    {

        return self::getData([
            "free_conds" => [
                "currencies.currency_is_active = 1"
            ],
        ]);

    }

    public function isCurrenciesRatesUpdated(): bool
    {

        $result = true;

        $currentDay = date("Y-m-d");

        $checkCurrency = self::getData([
            "free_conds" => [
                "date(updated_at) < '$currentDay'"
            ],
            "return_obj" => "yes"
        ]);

        if (is_object($checkCurrency)) {
            $result = false;
        }

        return $result;
    }


    public static function updateCurrenciesRates(array $currenciesCodes): void
    {

        \Cache::set('api_currencies_last_updated_date', date("Y-m-d"));

        foreach ($currenciesCodes as $currencyCode => $currencyRate) {

            $checkExist = currencies_m::where([
                "currency_code" => $currencyCode
            ])->first();

            if (!is_object($checkExist)) {
                continue;
            }

            $checkExist->update([
                "currency_rate" => $currencyRate
            ]);

            \Cache::set('api_currencies_' . $currencyCode, $currencyRate);

        }

    }


}
