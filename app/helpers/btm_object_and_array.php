<?php

function isset_and_array($var){
    return (isset($var)&&is_array($var)&&count($var));
}

function convert_inside_obj_to_arr($objs, $col, $objc_type = "object",$each_element_in_arr = "") {
    $arr = array();
    foreach ($objs as $key => $obj) {
        if ($objc_type == "object") {
            $arr[] = $obj->$col;
        }
        else {
            if ($each_element_in_arr != "")
            {
                $arr[] = array($obj["$col"]);
            }
            else{
                $arr[] = $obj["$col"];
            }
        }
    }
    return $arr;
}

function convertArrayKeyAndValueToTable(array $arr): string
{

    return View::make("general_form_blocks.convert_array_key_and_value_to_table")->with([
        "arr" => $arr
    ])->render();

}

function getIndexFromArray($arr, $index, $defaultValue = "")
{

    $indexes = explode(",", $index);

    foreach ($indexes as $index) {

        $index = trim($index);

        if (!isset($arr[$index])) {
            return $defaultValue;
        }

        $arr = $arr[$index];

    }

    return $arr;

}

function getIndexFromObject($arr, $index, $defaultValue = "")
{

    $indexes = explode(",", $index);

    foreach ($indexes as $index) {

        $index = trim($index);

        if (!isset($arr->{$index})) {
            return $defaultValue;
        }

        $arr = $arr->{$index};

    }

    return $arr;

}
