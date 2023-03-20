<?php

function cleanParamsForModel($arr)
{

    $purifiedArr = [];

    foreach ($arr as $key => $val) {

        $val               = Vsi($val);
        $purifiedArr[$key] = $val;

    }

    return $purifiedArr;

}

function Vsi($str)
{

    if($str === null){
        return null;
    }

    return \App\models\ModelUtilities::validateSqlInjection($str);

}


function JsF($field, $alias = "", $without_as = false)
{

    return \App\models\ModelUtilities::jsonField($field, $alias, $without_as);

}

function JsFT($field)
{

    return \App\models\ModelUtilities::jsonField($field, "", true);

}
