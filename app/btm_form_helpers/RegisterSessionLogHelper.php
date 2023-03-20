<?php


namespace App\btm_form_helpers;


use App\models\register\registers_sessions_logs_m;

class RegisterSessionLogHelper
{

    public static function createRegisterSession(
        $regSessionId,
        $itemId,
        $itemType,
        $operationType,
        $cashPaidAmount,
        $debitCardPaidAmount,
        $creditCardPaidAmount,
        $chequePaidAmount
    )
    {
        // $itemId        => order_id, gift_id
        // $itemType      => order, gift card
        // $operationType => increase, decrease

        $regSessionLog['register_session_id']     = $regSessionId;
        $regSessionLog['item_id']                 = $itemId;
        $regSessionLog['item_type']               = $itemType;
        $regSessionLog['operation_type']          = $operationType;
        $regSessionLog['cash_paid_amount']        = $cashPaidAmount;
        $regSessionLog['debit_card_paid_amount']  = $debitCardPaidAmount;
        $regSessionLog['credit_card_paid_amount'] = $creditCardPaidAmount;
        $regSessionLog['cheque_paid_amount']      = $chequePaidAmount;

        registers_sessions_logs_m::createRegisterSessionLog($regSessionLog);
    }


}
