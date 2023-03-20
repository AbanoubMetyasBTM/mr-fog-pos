<?php

namespace App\models;

use App\models\client\clients_m;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class wallets_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "wallets";

    protected $primaryKey = "wallet_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'wallet_amount'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            wallets.*
        "));


        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getAllWallets(): Collection
    {
        return self::getData();
    }

    public static function saveWallet($amount, $id = null)
    {
        if(is_null($id)){
            return self::create([
                "wallet_amount" => $amount,
            ]);
        }
        else {
            return
                self::where('wallet_id', '=', $id)->
                update(array(
                    "wallet_amount" => $amount,
                ));
        }


    }

    public static function getWalletById($wallet_id)
    {
        return self::getData([
            "free_conds" => [
                "wallet_id = $wallet_id"
            ],
            "return_obj" => "yes",
        ]);

    }
}
