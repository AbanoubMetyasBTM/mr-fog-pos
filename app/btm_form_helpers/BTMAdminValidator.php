<?php


namespace App\btm_form_helpers;


use App\form_builder\FormBuilder;
use Illuminate\Http\Request;

class BTMAdminValidator
{

    public static function checkValidation(Request $request, FormBuilder $formBuilder, $all_langs, $custom_rules = [])
    {
        $rules_values = [];
        $rules_itself = [];

        foreach ($formBuilder->required_fields as $field) {
            $rules_values[$field] = $request->get($field);
            $rules_itself[$field] = "required";
        }

        #region select fields
        foreach ($formBuilder->select_fields as $field => $attrs) {
            if (isset($attrs["required"]) && strpos($attrs["required"], "required") >= 0) {
                $rules_values[$field] = $request->get($field);
                $rules_itself[$field] = "required";
            }
        }
        #endregion

        #region normal fields
        if (
            isset($formBuilder->normal_fields_custom_attrs["default_required"]) &&
            $formBuilder->normal_fields_custom_attrs["default_required"] == "required"
        ) {
            foreach ($formBuilder->normal_fields as $field) {
                $rules_values[$field] = $request->get($field);
                $rules_itself[$field] = "required";
            }
        }

        if (
            isset($formBuilder->normal_fields_custom_attrs["custom_required"]) &&
            isset_and_array($formBuilder->normal_fields_custom_attrs["custom_required"])
        ) {
            foreach ($formBuilder->normal_fields_custom_attrs["custom_required"] as $field => $required) {
                if (strpos($required, "required") !== false) {
                    $rules_values[$field] = $request->get($field);
                    $rules_itself[$field] = "required";
                }
                else{
                    if($rules_itself[$field] == "required"){
                        unset($rules_values[$field]);
                        unset($rules_itself[$field]);
                    }
                }
            }
        }
        #endregion

        #region translate fields
        if (
            isset($formBuilder->cust_translate_fields_attrs["default_required"]) &&
            $formBuilder->cust_translate_fields_attrs["default_required"] == "required"
        ) {
            foreach ($formBuilder->translate_fields as $field) {
                foreach ($all_langs as $lang) {
                    $rules_values[$field . "_" . $lang->lang_title] = $request->get($field . "_" . $lang->lang_title);
                    $rules_itself[$field . "_" . $lang->lang_title] = "required";
                }
            }
        }

        if (
            isset($formBuilder->cust_translate_fields_attrs["custom_required"]) &&
            isset_and_array($formBuilder->cust_translate_fields_attrs["custom_required"])
        ) {
            foreach ($formBuilder->cust_translate_fields_attrs["custom_required"] as $field => $required) {
                foreach ($all_langs as $lang) {
                    if (strpos($required, "required") >= 0) {
                        $rules_values[$field."_".$lang->lang_title] = $request->get($field."_".$lang->lang_title);
                        $rules_itself[$field."_".$lang->lang_title] = "required";
                    }
                }
            }
        }
        #endregion

        foreach ($custom_rules as $field => $rule) {
            $rules_values[$field] = $request->get($field);

            if (!isset($rules_itself[$field])) {
                $rules_itself[$field] = $rule;
            }
            else {
                $rules_itself[$field] .= "|" . $rule;
            }
        }


        $validator = \Validator::make($rules_values, $rules_itself);

        if (count($validator->messages()) > 0) {
            return [
                "error" => implode("<br>", $validator->messages()->all())
            ];
        }

        return true;
    }


}
