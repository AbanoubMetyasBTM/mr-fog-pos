<?php

namespace App\Adapters\API\CurrenciesRates;

use Illuminate\Support\Collection;

interface ICurrenciesRatesAdapter{

    public function fetchAllCurrenciesNames();

    public function fetchCurrencies(array $currenciesCodes);

}
