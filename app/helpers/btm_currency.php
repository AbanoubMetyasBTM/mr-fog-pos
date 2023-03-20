<?php

use App\Exceptions\ApiException;
use App\Adapters\API\CurrenciesRates\ICurrenciesRatesAdapter;
use App\Transformers\StaticData\CurrenciesTransformer;
use App\models\currencies_m;

function updateCurrenciesRates()
{

    \Cache::forget('currencies_values_at_this_day');

    $currenciesRatesAdapter = app(ICurrenciesRatesAdapter::class);
    $currenciesTransformer  = new CurrenciesTransformer();

    $oldCurrencies          = currencies_m::getAllActiveCurrencies()->pluck("currency_code")->all();
    $updatedCurrenciesRates = $currenciesRatesAdapter->fetchCurrencies($oldCurrencies);
    $currenciesCodes        = $currenciesTransformer->transformCurrenciesCodesFromApi($updatedCurrenciesRates);

    currencies_m::updateCurrenciesRates($currenciesCodes);

}

function getCurrencyForApiFromCache(string $currencyCode, float $threshold = 0.00): float
{

    $last_updated_date = \Cache::get('api_currencies_last_updated_date');

    if ($last_updated_date != date("Y-m-d")) {
        updateCurrenciesRates();
        getCurrencyForApiFromCache($currencyCode, $threshold);
    }

    $rate = \Cache::get('api_currencies_' . $currencyCode);
    $rate = round(1 / $rate, 2); // we made this to reverse the rate

    if ($rate == null) {
        throw new ApiException(showContent("general_keywords.not_available_now_please_contact_support_for_help"), 16);
    }

    return round($rate + $threshold, 2);

}

function afterCurrency($price): string
{

    return afterCurrencyRate($price) . " " . getCurrencyCode();

}

function getCurrencyObj()
{

    $selected_currency = Session::get('selected_currency_for_agent', null);
    if ($selected_currency !== null) {
        return $selected_currency;
    }

    return Session::get('selected_currency');

}

function afterCurrencyRate($price)
{

    $selected_currency = getCurrencyObj();

    if (!is_object($selected_currency)) {
        return $price;
    }

    return round(($price) * $selected_currency->rate, 2);

}

function reverseCurrencyRate($price)
{

    $selected_currency = getCurrencyObj();

    if (!is_object($selected_currency)) {
        return $price;
    }

    return round(($price) / $selected_currency->rate, 2);

}

function getCurrencyCode()
{

    $selected_currency = getCurrencyObj();

    return $selected_currency->code;

}


function returnPriceAndCurrencyInArray($price): array
{
    return [
        "amount" => afterCurrencyRate($price),
        "code"   => "USD",
    ];
}
