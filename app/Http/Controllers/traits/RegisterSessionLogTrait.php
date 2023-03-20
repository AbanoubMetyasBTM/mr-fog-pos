<?php

namespace App\Http\Controllers\traits;

use App\btm_form_helpers\RegisterSessionLogHelper;
use App\models\register\registers_sessions_m;

trait RegisterSessionLogTrait
{
    public function createRegisterSession(
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

        RegisterSessionLogHelper::createRegisterSession(
            $regSessionId,
            $itemId,
            $itemType,
            $operationType,
            $cashPaidAmount,
            $debitCardPaidAmount,
            $creditCardPaidAmount,
            $chequePaidAmount
        );

    }

    private function getSessionWithCurrentUserId(){

        $userId   = session()->get("this_user_id");

        $checkSession = registers_sessions_m::getNotEndedRegisterSessionByEmployeeId($userId);
        if(is_object($checkSession)){
            session()->put('register_id', $checkSession->register_id);
            session()->put('register_session_id', $checkSession->id);
            return true;
        }

        return false;

    }

    public function checkIsSetRegisterSessionIdInSession()
    {
        if ( is_null(session()->get('register_id')) || is_null(session()->get('register_session_id'))){
            return $this->getSessionWithCurrentUserId();
        }

        return true;
    }
}
