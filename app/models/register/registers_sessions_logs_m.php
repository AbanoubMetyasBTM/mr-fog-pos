<?php

namespace App\models\register;

use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class registers_sessions_logs_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "register_session_logs";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'register_session_id', 'item_id', 'item_type', 'operation_type', 'cash_paid_amount',
        'debit_card_paid_amount', 'credit_card_paid_amount', 'cheque_paid_amount'
    ];

    public static function getData(array $attrs = [])
    {
        $results = self::select(\DB::raw("
            register_session_logs.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);
    }


    public static function getLogsByRegisterSessionId($registerSessionId)
    {
        return self::getData([
            "free_conds" => [
                "register_session_id = $registerSessionId"
            ],
        ]);

    }


    public static function createRegisterSessionLog($data)
    {
        self::create($data);
    }

    public static function getAllRegisterSessionLogs($attrs = [])
    {
        $results = self::select(\DB::raw("
            register_session_logs.*,
            register_session_logs.id as log_id,
            register_sessions.*,
            registers.*


        "))->
        join("register_sessions", "register_sessions.id", "=", "register_session_logs.register_session_id")->
        join("registers", "registers.register_id", "=", "register_sessions.register_id");


        $attrs['order_by'] = ['register_session_logs.id', 'desc'];
        return ModelUtilities::general_attrs($results, self::getAllRegisterSessionLogsCods($attrs));

    }

    private static function getAllRegisterSessionLogsCods($attrs)
    {
        $modelUtilitiesAttrs               = $attrs;
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];


        $allBranchRegistersIds = registers_m::getAllRegisterOrSpecificBranch()->pluck('register_id')->toArray();




        // filters


        if (isset($attrs["session_id"]) && !empty($attrs["session_id"]) && $attrs["session_id"] != "all") {

            $registerSession = registers_sessions_m::getSessionById($attrs["session_id"]);



            if (is_object($registerSession)){
                if (!in_array($registerSession->register_id, $allBranchRegistersIds)){
                    abort(404);
                    die();
                }

                $modelUtilitiesAttrs["free_conds"][] = "
                    register_session_logs.register_session_id = {$attrs["session_id"]}
                ";

            }
        }

        if (isset($attrs["session_id"]) && ($attrs["session_id"] == "all" || empty($attrs["session_id"]) )){

            $registersSessionsIds = registers_sessions_m::getAllRegisterSessionsOrSpecificBranchRegisterSessions()->
            pluck('id');

            $modelUtilitiesAttrs["whereIn"] = ["register_session_logs.register_session_id" => $registersSessionsIds];
        }


        if (isset($attrs["item_type"]) && !empty($attrs["item_type"]) && $attrs["item_type"] != 'all') {
            $modelUtilitiesAttrs["free_conds"][] = "
               register_session_logs.item_type = '{$attrs["item_type"]}'
            ";
        }

        if (isset($attrs["operation_type"]) && !empty($attrs["operation_type"]) && $attrs["operation_type"] != 'all') {
            $modelUtilitiesAttrs["free_conds"][] = "
               register_session_logs.operation_type = '{$attrs["operation_type"]}'
            ";
        }

        if (isset($attrs["date_from"]) && !empty($attrs["date_from"])) {
            $date = date('Y-m-d h:i:s', strtotime($attrs["date_from"]));
            $modelUtilitiesAttrs["free_conds"][] = "
               register_session_logs.created_at > '{$date}'
            ";
        }

        if (isset($attrs["date_to"]) && !empty($attrs["date_to"])) {
            $date = date('Y-m-d h:i:s', strtotime($attrs["date_to"]));
            $modelUtilitiesAttrs["free_conds"][] = "
               register_session_logs.created_at < '{$date}'
            ";
        }

        if (isset($attr["paginate"]) && !empty($attr["paginate"]) ){
            $modelUtilitiesAttrs["paginate"] = $attr["paginate"];
        }




        return $modelUtilitiesAttrs;
    }



}
