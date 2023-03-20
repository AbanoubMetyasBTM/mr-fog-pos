<?php

namespace App\Http\Middleware\API;

use App\Exceptions\notPermittedApi;
use Closure;
use Illuminate\Http\Request;

class APIGate
{

    private $requiredHeaders = [
        "accept"            => ["application/json"],
        "accept-language"   => [],
        "app-version"       => [],
        "device-name"       => [],
        "device-os-version" => [],
        "device-udid"       => [],
        "device-type"       => ["android", "ios"],
        "device-push-token" => [],
    ];

    public function handle(Request $request, Closure $next)
    {

        $isAllowed          = 1;

        $requestHeaders     = clean($request->headers->all());
        $requestHeadersKeys = array_keys($requestHeaders);
        $requestHeadersKeys = array_map('strtolower',$requestHeadersKeys);

        foreach($this->requiredHeaders as $key => $allowedValues)
        {

            if(
                !in_array($key, $requestHeadersKeys) ||
                !(isset($requestHeaders[$key][0]) && !empty($requestHeaders[$key][0])) ||
                (count($allowedValues) && !(in_array(strtolower($requestHeaders[$key][0]), $allowedValues)))
            )
            {
                $isAllowed = 0;
                break;
            }

        }

        if($isAllowed == 0)
        {
            // throw exception
            throw new notPermittedApi();
        }


        return $next($request);
    }

}
