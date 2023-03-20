<?php

namespace App\models\branch;

use App\models\inventory\inventories_m;
use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class branch_inventory_m extends \Eloquent
{


    protected $table = "branch_inventory";

    protected $primaryKey = "id";

    protected $fillable = [
        'branch_id', 'inventory_id', 'is_main_inventory'
    ];

    public $timestamps = false;

    public static function getData(array $attr = [])
    {
        $results = self::select(\DB::raw("
            branch_inventory.*,
        "));
        return ModelUtilities::general_attrs($results, $attr);
    }

    public static function getAllBranchesInventories($attr = []): Collection
    {
        $modelUtilitiesAttrs               = [];
        $modelUtilitiesAttrs["cond"]       = [];
        $modelUtilitiesAttrs["free_conds"] = [];

        $results = self::select(\DB::raw("
            branch_inventory.*,
            branches.branch_name,
            inventory_places.inv_id,
            inventory_places.inv_name

        "));

        $results = $results->
        join("branches","branches.branch_id","=","branch_inventory.branch_id")->
        join("inventory_places","inventory_places.inv_id","=","branch_inventory.inventory_id");


        $currentBranchId = \Session::get("current_branch_id");
        if (!empty($currentBranchId)){
            $attr["branch_id"] = $currentBranchId;
        }

        // filters
        if (isset($attr["branch_id"]) && !empty($attr["branch_id"]) && $attr["branch_id"] != 'all'){

            $branch_id = $attr["branch_id"];
            $modelUtilitiesAttrs["free_conds"][] = "
                branch_inventory.branch_id = $branch_id
            ";
        }


        if (isset($attr["inventory_id"]) && !empty($attr["inventory_id"]) && $attr["inventory_id"] != 'all'){

            $inventory_id = $attr["inventory_id"];
            $modelUtilitiesAttrs["free_conds"][] = "
               branch_inventory.inventory_id = $inventory_id
            ";
        }


        if (isset($attr["is_main_inventory"]) && !empty($attr["is_main_inventory"]) &&$attr["is_main_inventory"] != 'all'){

            $is_main_inventory = $attr["is_main_inventory"];
            $modelUtilitiesAttrs["free_conds"][] = "
               branch_inventory.is_main_inventory = $is_main_inventory
            ";
        }

        return ModelUtilities::general_attrs($results, $modelUtilitiesAttrs);

    }


    public static function getMainInvBranchesExceptSpecificBranchInv($branchId , $invId)
    {
        return self::query()
            ->where('branch_id', '<>', $branchId)
            ->where('inventory_id', '<>', $invId)
            ->where('is_main_inventory', '=', 1)
            ->get();
    }

    public static function getMainInvOfBranch($branchId)
    {
        return self::where('branch_id', '=', $branchId)
            ->where('is_main_inventory', '=', 1)
            ->first();
    }

    public static function getSearchInventoriesData()
    {

        $currentBranchId = \Session::get("current_branch_id");

        if ($currentBranchId == null) {
            return inventories_m::getAllInventories();
        }

        return branch_inventory_m::getAllBranchesInventories();
    }


    public static function checkItHasMainInventory($branch_id): bool
    {
        $res =self::getMainInvOfBranch($branch_id);

        if(is_object($res)){
            return true;
        }
        return false;
    }

}
