<?php

namespace App\Transformers\StaticData;

use App\Transformers\Transformer;

class CurrenciesTransformer extends Transformer
{

    public function transformCurrencies(array $items): array
    {

        $allData = [];

        foreach ($items as $key => $item) {
            $allData[] = $this->_transformCurrenciesItem($item);
        }

        return $allData;

    }

    private function _transformCurrenciesItem(object $item): array
    {

        $data = [];

        $data["id"]    = $item->id;
        $data["code"]  = $item->currency_code;
        $data["name"]  = $item->currency_name;
        $data["rate"]  = $item->currency_rate;
        $data["image"] = get_image_from_json_obj($item->currency_img_obj);

        return $data;

    }

    public function transformCurrenciesCodesFromApi(array $items): array
    {

        $allData = [];

        foreach ($items as $key => $item) {

            $key           = str_replace(config("default_currency.label"), '', $key);
            $allData[$key] = $item;
        }

        return $allData;

    }


}
