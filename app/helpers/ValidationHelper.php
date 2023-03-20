<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class ValidationHelper {


    public function __construct() {

    }

    public function getValidationErrorsWithRequest(Validator $validator) {

        if ($validator->fails()) {

            $messages   = $validator->errors();
            $errors     = [];

            foreach ($messages->jsonSerialize() as $field => $msg) {
                $errors[]   = [
                    "field"     => $field,
                    "errorCode" => "Validation",
                    "errorMsg"  => $msg[0]
                ];
            }

            return $errors;
        }

        return true;
    }

    public function applyValidator(Request $request, array $rules, array $messages)
    {

        return \Validator::make(clean($request->all()), $rules, $messages);

    }

}
