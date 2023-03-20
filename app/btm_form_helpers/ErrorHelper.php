<?php


namespace App\btm_form_helpers;


use App\helpers\utility;

class ErrorHelper
{

    public static function _getRequestLog($request): array
    {
        if($request==""){
            return [];
        }

        $dataToLog = [];

        $dataToLog["Time"]              = gmdate("F j, Y, g:i a");
        $dataToLog["IP Address"]        = $request->ip();
        $dataToLog["URL"]               = $request->fullUrl();
        $dataToLog["Method"]            = $request->method();
        $dataToLog["Headers"]           = json_encode($request->headers->all());
        $dataToLog["Accept-Header"]     = $request->headers->get('Accept', '');
        $dataToLog["Client-Token"]      = $request->headers->get('client-token', '');
        $dataToLog["Client-Session-ID"] = $request->headers->get('client-session-id', '');
        $dataToLog["Input"]             = $request->getContent();

        return $dataToLog;
    }


    public static function _notifyDevelopers($request, $message, $source)
    {

        $header = "--canda pos Website-- source[$source] critical issue need to be fixed #" . date("Y-m-d H:i");

        $getLog = self::_getRequestLog($request);

        utility::sendErrorLogEmail($header, $message, $getLog);

    }

    public static function handleException(\Exception $exception, $request=""){
        $exceptionFile = $exception->getFile();
        $exceptionLine = $exception->getLine();

        $msg = "File : $exceptionFile - Line : $exceptionLine - Error : " . $exception->getMessage();

        self::_notifyDevelopers($request, $msg, "Main POS");

        return $msg;
    }

}
