<?php

namespace App\btm_form_helpers;

use App\models\transactions_log_m;
use App\models\wallets_m;

class WalletHelper
{


    public static function withdrawMoney($attrs)
    {

        /*
            $attrs = [
                "moneyAmount"                   => "100",
                "walletId"                      => "",
                "walletOwnerName"               => "",
                "notes"                         => "",
                "transactionType"               => "",
                "transactionCurrency"           => "",
                "transactionMoneyType"          => "",
                "passEnoughMoneyAtWalletCheck"  => "",
            ];
        */

        $moneyAmount = floatval($attrs["moneyAmount"]);

        if ($moneyAmount == 0) {
            return [
                "error" => "money amount is zero"
            ];
        }


        $walletObj   = wallets_m::find($attrs["walletId"]);

        if (!is_object($walletObj)) {
            return [
                "error" => "invalid wallet id"
            ];
        }

        if (
            (
                !isset($attrs["passEnoughMoneyAtWalletCheck"]) ||
                $attrs["passEnoughMoneyAtWalletCheck"] === false
            ) &&
            $moneyAmount > $walletObj->wallet_amount
        ) {
            var_dump("you can not get from {$attrs["walletOwnerName"]} what does not have");
            return [
                "error" => "you can not get from {$attrs["walletOwnerName"]} what does not have"
            ];
        }


        //decrease wallet for parent agent
        $newWallet = ($walletObj->wallet_amount - $moneyAmount);
        wallets_m::saveWallet($newWallet, $attrs["walletId"]);

        if (!isset($attrs["transactionType"]) || empty($attrs["transactionType"])) {
            $attrs["transactionType"] = "get_money_from_wallet";
        }

        //add transaction log
        transactions_log_m::create([
            'wallet_id'             => $attrs["walletId"],
            'transaction_type'      => $attrs["transactionType"],
            'transaction_operation' => "decrease",
            'transaction_currency'  => $attrs["transactionCurrency"],
            'transaction_amount'    => $moneyAmount,
            'transaction_notes'     => $attrs["notes"],
            'money_type'            => $attrs["transactionMoneyType"] ?? null,
        ]);

        return true;
    }

    public static function depositMoney($attrs)
    {

        /*
            $attrs = [
                "moneyAmount"           => "100",
                "walletId"              => "",
                "walletOwnerName"       => "",
                "notes"                 => "",
                "transactionType"       => "",
                "transactionCurrency"   => "",
                "transactionMoneyType"  => "",
            ];
        */

        $moneyAmount = floatval($attrs["moneyAmount"]);

        if ($moneyAmount == 0) {
            return [
                "error" => "money amount is zero"
            ];
        }

        $walletObj   = wallets_m::find($attrs["walletId"]);

        if (!is_object($walletObj)) {
            return [
                "error" => "invalid wallet id"
            ];
        }

        $newWallet = ($walletObj->wallet_amount + $moneyAmount);
        wallets_m::saveWallet($newWallet, $attrs["walletId"]);


        if (!isset($attrs["transactionType"]) || empty($attrs["transactionType"])) {
            $attrs["transactionType"] = "add_money_to_wallet";
        }

        transactions_log_m::create([
            'wallet_id'             => $attrs["walletId"],
            'transaction_type'      => $attrs["transactionType"],
            'transaction_operation' => "increase",
            'transaction_currency'  => $attrs["transactionCurrency"],
            'transaction_amount'    => $moneyAmount,
            'transaction_notes'     => $attrs["notes"],
            'money_type'            => $attrs["transactionMoneyType"] ?? null,
        ]);

        return true;
    }

}
