<?php

namespace App\Adapters\API\CurrenciesRates\Implementation;

use App\Adapters\API\CurrenciesRates\ICurrenciesRatesAdapter;
use App\Exceptions\CustomApiOtherException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;

class CurrenciesRatesAdapter implements ICurrenciesRatesAdapter
{

    private   $apiKey = "Jk5tkQXGh3hUHMGr4n380ymW4fwZzvyO";
    protected $httpRequest;

    protected $basicHeaders = [
        "Content-Type" => "text/plain",
        "apikey"       => "",
    ];

    public function __construct()
    {

        $this->httpRequest            = new Client([]);
        $this->basicHeaders["apikey"] = $this->apiKey;

    }

    public function fetchAllCurrenciesNames()
    {

        try {

            $baseUrl = "https://api.currencylayer.com/list?access_key=$this->apiKey";

            $response = $this->httpRequest->get($baseUrl, [
                "headers" => $this->basicHeaders,
            ]);

            $getContent = $response->getBody()->getContents();
            $getContent = json_decode($getContent, true);

            if ($getContent["success"] == true) {
                return $getContent["currencies"];
            }
            else {
                return [];
            }

        } catch (RequestException $exception) {
            $this->fireException($exception);
        } catch (\Exception $exception) {
            $this->fireException($exception);
        }

    }

    public function fetchCurrencies(array $currenciesCodes)
    {

        try {

            $currenciesCodes = implode(',', $currenciesCodes);
            $baseUrl         = "https://api.apilayer.com/fixer/latest?symbols=$currenciesCodes&base=EUR";

            $response = $this->httpRequest->get($baseUrl, [
                "headers" => $this->basicHeaders,
            ]);


            $getContent = $response->getBody()->getContents();
            $getContent = json_decode($getContent, true);


            if ($getContent["success"] == true) {
                return $getContent["rates"];
            }
            else {
                return [];
            }

        } catch (RequestException $exception) {
            $this->fireException($exception);
        } catch (\Exception $exception) {
            $this->fireException($exception);
        }

    }

    protected function fireException(\Exception $exception)
    {
        $errorMessage = $exception->getMessage();

        throw new CustomApiOtherException($errorMessage);

    }


}
