<?php

namespace App\models\register;

use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class registers_sessions_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "register_sessions";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'register_id', 'employee_id', 'register_start_at', 'register_start_cash_amount',
        'register_closed_at', 'register_end_cash_amount', 'register_end_debit_count',
        'register_end_debit_amount', 'register_end_credit_count', 'register_end_credit_amount',
        'register_end_cheque_count', 'register_end_cheque_amount', 'approved_by_admin', 'approved_by_user_id'
    ];

    public static function getData(array $attrs = [])
    {
        $results = self::select(\DB::raw("
            register_sessions.*
        "));

        if(isset($attrs["need_register_join"])){
            $results = $results->addSelect(\DB::Raw("
                register_name
            "))->
            join("registers","registers.register_id","=","register_sessions.register_id");
        }

        if(isset($attrs["need_employee_join"])){
            $results = $results->addSelect(\DB::Raw("
                employees.full_name as employee_name
            "))->
            join("users as employees","employees.user_id","=","register_sessions.employee_id");
        }

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getAllRegistersSessions(): Collection
    {
        return self::getData();
    }

    public static function checkIfRegisterHasSessions($registerId): bool
    {
        $sessions =
            self::query()
                ->where('register_id', '=', $registerId)
                ->get();

        if (!count($sessions)){
            return false;
        }
        return true;

    }


    public static function getAllNotEndedRegistersSessions()
    {
        return self::select(\DB::raw("
            register_sessions.*,
            users.full_name
        "))->
        join('users','users.user_id','register_sessions.employee_id')->
        where('register_closed_at', '=', null)->
        get();
    }

    public static function getNotEndedRegisterSessionByRegisterId($registerId)
    {
        return self::query()->
            where('register_id', '=', $registerId)->
            where('register_closed_at', '=', null)->
            limit(1)->
            first();
    }

    public static function getNotEndedRegisterSessionByEmployeeId($empId)
    {
        return self::query()->
        where('employee_id', '=', $empId)->
        where('register_closed_at', '=', null)->
        limit(1)->
        first();
    }


    public static function createRegisterSession($data)
    {
        return self::create($data);
    }

    public static function updateRegisterSession($registerSessionId, $data)
    {
        self::query()->where('id','=', $registerSessionId)->update($data);
    }

    public static function getRegisterSessionsByRegisterId($registerId)
    {
        return self::getData([
            "free_conds" => [
                "register_id = $registerId"
            ]
        ]);

    }


    private static function getAllRegisterSessionsOrSpecificBranchRegisterSessionsConds($attrs)
    {
        $modelUtilitiesAttrs               = $attrs;
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];


        $allBranchRegistersIds = registers_m::getAllRegisterOrSpecificBranch()->pluck('register_id')->toArray();


        if (!isset($attrs["register_id"])){
            $attrs['register_id'] = 'all';
        }



        // filters
        if (isset($attrs["register_id"]) && !empty($attrs["register_id"]) && $attrs["register_id"] != "all") {
            if (!in_array($attrs["register_id"], $allBranchRegistersIds)){
                abort(404);
                die();
            }

            $modelUtilitiesAttrs["free_conds"][] = "
               register_sessions.register_id = '{$attrs["register_id"]}'
            ";
        }

        if (isset($attrs["session_id"]) && !empty($attrs["session_id"]) && $attrs["session_id"] != "all") {

            $registerSession = registers_sessions_m::getSessionById($attrs["session_id"]);

            if (is_object($registerSession)){
                if (!in_array($registerSession->register_id, $allBranchRegistersIds)){
                    abort(404);
                    die();
                }

                $modelUtilitiesAttrs["free_conds"][] = "
                    register_sessions.id = '{$attrs["session_id"]}'
                ";

            }
        }


        if ($attrs["register_id"] == "all" || empty($attrs["register_id"]) ){
            $modelUtilitiesAttrs["whereIn"] = ["register_sessions.register_id" => $allBranchRegistersIds];
        }


        if (isset($attrs["date_from"]) && !empty($attrs["date_from"])) {
            $date = date('Y-m-d h:i:s', strtotime($attrs["date_from"]));
            $modelUtilitiesAttrs["free_conds"][] = "
               register_sessions.created_at > '{$date}'
            ";
        }

        if (isset($attrs["date_to"]) && !empty($attrs["date_to"])) {
            $date = date('Y-m-d h:i:s', strtotime($attrs["date_to"]));
            $modelUtilitiesAttrs["free_conds"][] = "
               register_sessions.created_at < '{$date}'
            ";
        }

        if (isset($attr["paginate"]) && !empty($attr["paginate"]) ){
            $modelUtilitiesAttrs["paginate"] = $attr["paginate"];
        }

        return $modelUtilitiesAttrs;

    }

    public static function getAllRegisterSessionsOrSpecificBranchRegisterSessions($attrs = [])
    {

        $results = self::select(\DB::raw("
            register_sessions.*,
            registers.register_name,
            employees.full_name as employee_name

        "))->
        join("registers","registers.register_id","=","register_sessions.register_id")->
        join("users as employees","employees.user_id","=","register_sessions.employee_id");

        $currentBranchId = \Session::get("current_branch_id");
        if (!empty($currentBranchId)){
            $branchRegistersIds = registers_m::getAllRegisterOrSpecificBranch([])->pluck('register_id');

            $results = $results->whereIn('register_sessions.register_id', $branchRegistersIds);
        }



        return ModelUtilities::general_attrs($results, self::getAllRegisterSessionsOrSpecificBranchRegisterSessionsConds($attrs));

    }


    public static function getSessionById($sessionId)
    {
        return self::getData([
            "free_conds" => [
                "id = $sessionId"
            ],
            "return_obj" => "yes"
        ]);
    }
}
