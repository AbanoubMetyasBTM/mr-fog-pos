<?php

namespace App\models\register;

use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class registers_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "registers";

    protected $primaryKey = "register_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'branch_id', 'register_name'
    ];

    public static function getData(array $attrs = [])
    {
        $results = self::select(\DB::raw("
            registers.*
        "));

        if(isset($attrs["need_branch_join"])){

            $results = $results->addSelect(\DB::Raw("
                branch_name
            "))->leftJoin("branches","branches.branch_id","=","registers.branch_id");
        }
        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getAllRegistersWithBranchesNames(): Collection
    {
        return self::getData([
            "need_branch_join" => true
        ]);
    }


    public static function getAllRegisterOrSpecificBranch(array $attrs = []): Collection
    {

        if (!isset($attrs["free_conds"])){
            $attrs["free_conds"] = [];
        }

        $currentBranchId = \Session::get("current_branch_id");
        if (!empty($currentBranchId)){
            $attrs["free_conds"][] = "branches.branch_id = $currentBranchId";
        }
        $attrs["need_branch_join"]           = true;

        return self::getData($attrs);

    }

    public static function checkItHasAtLeastOneRow($branch_id): bool
    {

        $results = self::where('branch_id','=',$branch_id)->
        limit(1)->
        first();

        if(is_object($results)){
            return true;
        }
        return false;

    }

}
