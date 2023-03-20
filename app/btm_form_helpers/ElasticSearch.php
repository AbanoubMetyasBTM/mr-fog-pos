<?php

namespace App\btm_form_helpers;

use Elasticsearch\ClientBuilder;

class ElasticSearch
{

    private static $client   = "";
    private static $index    = "canda_erp";
    private static $doc_type = "data";

    public static function SetClient()
    {
        self::$client = ClientBuilder::create()->build();
    }

    public static function CheckSetClient()
    {
        if (self::$client == "") {
            self::SetClient();
        }
    }

    public static function SetIndex($index)
    {
        self::$index = $index;
    }

    public static function SetDocType($type)
    {
        self::$doc_type = $type;
    }

    public static function SaveRow($id, $data)
    {
        self::CheckSetClient();

        $params = [
            'index' => self::$index,
            'type'  => self::$doc_type,
            'id'    => $id,
            'body'  => $data
        ];

        $response = self::$client->index($params);

        if (isset($response["result"]) && in_array($response["result"], ["updated", "created"])) {
            return true;
        }

        return false;
    }

    private static function oldsetAttrs($attrs, $params)
    {

        if (isset($attrs["array_key"])) {
            if (!isset($params["body"]["query"]["bool"])) {
                $params["body"]["query"]["bool"] = [];
            }

            if (!isset($params["body"]["query"]["bool"]["filter"])) {
                $params["body"]["query"]["bool"]["filter"] = [];
            }

            if (!isset($params["body"]["query"]["bool"]["filter"]["terms"])) {
                $params["body"]["query"]["bool"]["filter"]["terms"] = [];
            }

            $params["body"]["query"]["bool"]["filter"]["terms"] = [
                $attrs["array_key"] => $attrs["array_values"]
            ];
        }

        if (isset($attrs["conditions"]) && isset_and_array($attrs["conditions"])) {

            if (!isset($params["body"]["query"]["match"])) {
                $params["body"]["query"]["match"] = [];
            }

            foreach ($attrs["conditions"] as $key => $val) {

                $params["body"]["query"]["match"][$key] = $val;
            }
        }

        if (isset($attrs["like_key"])) {
            $params["body"]["query"]["wildcard"] = [
                $attrs["like_key"] => "*" . $attrs["like_val"] . "*"
            ];
        }

        return $params;
    }

    private static function setAttrs($attrs, $params)
    {

        $params["body"]["query"]                     = [];
        $params["body"]["query"]                     = [];
        $params["body"]["query"]["bool"]             = [];

        $musts     = [];
        $not_musts = [];

        if (isset($attrs["array_key"])) {

            $shoulds = [];

            foreach ($attrs["array_values"] as $value){
                $shoulds[]=[
                    "match" => [
                        $attrs["array_key"] => $value
                    ]
                ];
            }

            $musts[] = [
                "bool" => [
                    "should" => $shoulds
                ]
            ];

        }

        //where field=value
        if (isset($attrs["conditions"]) && isset_and_array($attrs["conditions"])) {

            foreach ($attrs["conditions"] as $key => $val) {

                $musts[] = [
                    "match" => [
                        $key => $val
                    ]
                ];

            }
        }

        // where field is not null
        if (isset($attrs["not_null"]) && isset_and_array($attrs["not_null"])) {

            foreach ($attrs["not_null"] as $key => $field) {

                $musts[] = [
                    "regexp" => [
                        "$field" => ".+"
                    ]
                ];

            }
        }

        //where field like '%val%'
        if (isset($attrs["like_key"])) {

            $likeValArr = explode(" ",$attrs["like_val"]);

            foreach ($likeValArr as $val){
                $musts[] = [
                    "wildcard" => [
                        $attrs["like_key"] => "*" . $val . "*"
                    ]
                ];
            }

        }

        if (isset_and_array($musts)){
            $params["body"]["query"]["bool"]["must"]   = [];
            $params["body"]["query"]["bool"]["must"]   = $musts;
        }

        if (isset_and_array($not_musts)){
            $params["body"]["query"]["bool"]["must_not"] = [];
            $params["body"]["query"]["bool"]["must_not"] = $not_musts;
        }

        if(isset($attrs["order_by"])){
            $params["body"]["sort"]= [
                $attrs["order_by"][0] => $attrs["order_by"][1]
            ];
        }


        return $params;
    }


    public static function SearchRows($attrs = [], $size = 10)
    {
        self::CheckSetClient();

        $params = [
            'index' => self::$index,
            'body'  => [
                'query' => [],
                "size"  => $size
            ]
        ];

        $params = self::setAttrs($attrs, $params);

//        dump("elastic params");
//        dump($params);
//        dump(json_encode($params,JSON_PRETTY_PRINT));
//        dump(json_encode($params));

        $response = self::$client->search($params);

        if ($response["hits"]["total"] > 0) {
            return collect($response["hits"]["hits"])->pluck("_source");
        }

        return collect($response);
    }

    public static function DeleteRow($id)
    {
        self::CheckSetClient();

        $params = [
            'index' => self::$index,
            'type'  => self::$doc_type,
            'id'    => $id,
        ];

        self::$client->delete($params);
    }


}
