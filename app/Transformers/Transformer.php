<?php

namespace App\Transformers;


use Carbon\Carbon;

abstract class Transformer
{

    public function transformSlider(object $item,$sliderKey,$defaultImageUrl=""){

        $gallery = [];
        $slider  = json_decode($item->{$sliderKey});

        if(!is_array($slider) || !count($slider)){

            if(empty($defaultImageUrl)){
                return $gallery;
            }

            $gallery[] = $defaultImageUrl;
            return $gallery;
        }

        foreach ($slider as $img){
            $gallery[] = get_image_from_json_obj($img);
        }

        return $gallery;

    }

    public function convertDateTimeFromUTC($dateTimeInUTC, $convertedTimeZone = "UTC") :string
    {
        $convertedDateTime   = Carbon::createFromFormat('Y-m-d H:i:s', "$dateTimeInUTC", "UTC")
                ->setTimezone("$convertedTimeZone")->toDateTimeString();

        return $convertedDateTime;
    }

    public function _calcRateValue(float $rate, float $total) : float
    {

        $rateValue = round((($rate * $total) / 100), 2);

        return $rateValue;
    }

    public function _calcPriceWithMarkup(float $rate, float $total) : float
    {

        $rateValue = round((($rate * $total) / 100), 2);

        return round(($total + $rateValue), 2);
    }


}

