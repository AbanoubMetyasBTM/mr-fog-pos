<?php


namespace App\models;


use App\form_builder\FormBuilder;

class ModelUtilities
{

    public static function validateSqlInjection($string){

        $except = array('\\', '/', '*', '?', '"', "'", '<', '>', '|', '+', '&', '#', ';', '[', ']'); // because of date issue, remove (:, space)
        return str_replace($except, '', $string);

    }

    public static function jsonField($field,$alias="",$without_as=false){
        $default_lang_title=config("default_language.primary_lang_title");

        if($alias == "" && strpos($field,'.')>=0){
            $alias = explode('.',$field)[1];
        }

        if($alias == ""){
            $alias = $field;
        }


        //->> operator not working at george's server
        //$raw_sql.="$base_table_name.$field->>'$.$default_lang_title' as $field";

        return "
            IF(
                JSON_VALID($field),
                JSON_UNQUOTE(JSON_EXTRACT($field,'$.$default_lang_title')),
                ''
            )

        ".($without_as?"":" as $alias ");
    }

    public static function getTranslateData($result,$base_table_name,FormBuilder $formBuilder,$additional_fields=[]){

        $raw_sql = self::setRawSqlForTranslateFields($base_table_name, $formBuilder,$additional_fields);
        if(!empty($raw_sql))
        {
            return $result->addSelect(\DB::raw($raw_sql));
        }

        return $result;

    }

    public static function setRawSqlForTranslateFields($base_table_name,FormBuilder $formBuilder,$additional_fields=[]){

        $raw_sql="";

        foreach ($additional_fields as $key=>$field){
            $raw_sql.=self::jsonField($key,$field).",";
        }

        foreach ($formBuilder->translate_fields as $key=>$field){
            $raw_sql.=self::jsonField("$base_table_name.$field",$field);

            if(count($formBuilder->translate_fields)-1>$key){
                $raw_sql.=",";
            }
        }

        return $raw_sql;
    }

    public static function  general_attrs($results,$attrs){

        if(isset($attrs["addSelect"])){

            $results = $results->addSelect(\DB::raw($attrs["addSelect"]));

        }

        if(isset($attrs["cond"])){
            $attrs["additional_and_wheres"]=$attrs["cond"];
        }

        if(isset($attrs["free_conds"]) && count($attrs["free_conds"])){
            $attrs["free_conditions"]= implode(" AND ",$attrs["free_conds"]);
        }

        if (isset($attrs["additional_and_wheres"]) && is_array($attrs["additional_and_wheres"]) && count($attrs["additional_and_wheres"]))
        {
            $results        = $results->where($attrs["additional_and_wheres"]);
        }

        if (isset($attrs["whereIn"])) {
            foreach ($attrs["whereIn"] as $key => $vals) {
                $results = $results->whereIn($key, $vals);
            }
        }


        if (isset($attrs["free_conditions"]) && !empty($attrs["free_conditions"]))
        {
            $results        = $results->whereRaw($attrs["free_conditions"]);
        }

        if (isset($attrs["order_by"]))
        {
            $results        = $results->orderBy($attrs["order_by"][0],$attrs["order_by"][1] ?? "asc");
        }

        if (isset($attrs["order_by_col"]) && !empty($attrs["order_by_col"]))
        {
            $results        = $results->orderBy($attrs["order_by_col"],$attrs["order_by_type"] ?? "asc");
        }

        if (isset($attrs["without_get"]))
        {
            return $results;
        }


        if (isset($attrs["limit"]) && $attrs["limit"] > 0)
        {
            $results        = $results->limit($attrs["limit"])->offset($attrs["offset"] ?? 0)->get();
        }
        else if (isset($attrs["paginate"]) && $attrs["paginate"] > 0)
        {
            $results        = $results->paginate($attrs["paginate"]);
        }
        else{

            if (isset($attrs["return_obj"]) && $attrs["return_obj"] != "no")
            {
                $results    = $results->limit(1);
            }

            $results        = $results->get();
        }

        if (isset($attrs["return_obj"]) && $attrs["return_obj"] != "no")
        {
            $results    = $results->first();
        }

        return $results;
    }


}
