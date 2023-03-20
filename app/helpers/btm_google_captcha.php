<?php

function checkRecaptcha(\Illuminate\Http\Request $request, $field = "g-recaptcha-response")
{

    $Url = "https://www.google.com/recaptcha/api/siteverify";

    $data = array(
        'secret'   => '6LeCmbUUAAAAADf9pnLTWrKLJ7PCoQnEoyJOpYDZ',
        'response' => $request->get($field)
    );

    $ch = curl_init($Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $verifyResponse = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($verifyResponse);

    if (!$responseData->success) {
        return false;
    }

    return true;
}
