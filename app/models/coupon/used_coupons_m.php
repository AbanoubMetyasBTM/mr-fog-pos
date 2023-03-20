<?php

namespace App\models\coupon;

use App\models\ModelUtilities;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class used_coupons_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "used_coupons";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'coupon_id', 'branch_id', 'client_id',
        'order_id', 'discount_value'
    ];

    public static function getData(array $attrs = [])
    {
        $results = self::select(\DB::raw("
            used_coupons.*
        "));


        return ModelUtilities::general_attrs($results, $attrs);
    }


    public static function createUsedCoupon($couponId, $branchId, $clientId, $orderId, $discountValue)
    {
        self::create([
            'coupon_id'      => $couponId,
            'branch_id'      => $branchId,
            'client_id'      => $clientId,
            'order_id'       => $orderId,
            'discount_value' => $discountValue
        ]);
    }

    public static function checkIfCouponUsedBySpecificClient($clientId, $couponId)
    {
        return self::getData([
            "free_conds" => [
                "client_id = $clientId",
                "coupon_id = $couponId"
            ],
            "return_obj" => "yes"
        ]);

    }

}
