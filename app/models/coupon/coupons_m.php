<?php

namespace App\models\coupon;

use App\models\ModelUtilities;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class coupons_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "coupons";

    protected $primaryKey = "coupon_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'branch_id', 'coupon_title', 'coupon_code', 'coupon_start_date',
        'coupon_end_date', 'coupon_code_type', 'coupon_code_value',
        'coupon_is_active', 'coupon_limited_number', 'coupon_used_times'
    ];

    public static function getData(array $attrs = [])
    {
        $results = self::select(\DB::raw("
            coupons.*
        "));


        if(isset($attrs["need_branch_join"])){
            $results = $results->leftJoin("branches","branches.branch_id","=","coupons.branch_id");
            $results = $results->addSelect(\DB::Raw("
                branch_name
            "));
        }


        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getAllCouponsWithBranchesNames(): Collection
    {
        return self::getData([
            "need_branch_join" => true
        ]);
    }


    public static function getAllCouponsOrSpecificBranch(array $attrs = []): Collection
    {

        if (!isset($attrs["free_conds"])){
            $attrs["free_conds"] = [];
        }


        $currentBranchId = \Session::get("current_branch_id");
        if (!empty($currentBranchId)){
            $attrs["free_conds"][] = "branches.branch_id = $currentBranchId";
        }
        $attrs[ "need_branch_join"] = true;

        return self::getData($attrs);

    }

    public static function getCouponByCodeAndBranchId($couponCode, $branchId)
    {
        $currentTime = Carbon::now();

        return
            self::
            where('coupon_code', "=", $couponCode)->
            whereRaw("coupon_limited_number > coupon_used_times")->
            where("coupon_is_active", "=", 1)->
            where("coupon_start_date","<",$currentTime)->
            where("coupon_end_date",">", $currentTime)->
            whereRaw(\DB::raw("(coupons.branch_id = 0 or coupons.branch_id=$branchId)"))->
            limit(1)->
            get()->
            first();
    }

    public static function increaseCouponUsedTimes($couponId)
    {
        $couponObj    = self::where('coupon_id','=', $couponId)->first();
        $newUsedTimes = intval($couponObj->coupon_used_times) + 1;

        self::where('coupon_id','=', $couponId)->
        update([
            'coupon_used_times' => $newUsedTimes
        ]);
    }




}
