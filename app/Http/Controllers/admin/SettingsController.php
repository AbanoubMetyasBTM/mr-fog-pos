<?php

namespace App\Http\Controllers\admin;

use App\btm_form_helpers\image;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\notificationsTrait;
use App\models\employee_action_log_m;
use App\models\settings_m;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class SettingsController extends AdminBaseController
{

    use notificationsTrait;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Settings");
    }

    private function getSettingsImgObjs(Collection $img_settings):array
    {

        $img_objs     = [];

        foreach ($img_settings as $img_setting) {
            $img_objs[$img_setting->setting_key] = json_decode($img_setting->setting_value);
        }

        return $img_objs;

    }

    private function getImageOldPath(array $oldImages,string $fieldName):string
    {

        if(isset($oldImages[$fieldName]) && isset($oldImages[$fieldName]->path)){
            return $oldImages[$fieldName]->path;
        }

        return "";

    }

    private function saveSettingsAtCache($key,$value){

        \Cache::forever("settings.{$key}",$value);

    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/settings","can_edit_settings");


        $settings               = settings_m::all();
        $file_settings          = $settings->where("setting_type", "file");
        $this->data["settings"] = $settings->groupBy("setting_key")->all();
        $this->data["img_objs"] = $this->getSettingsImgObjs($file_settings);

        $doNotSaveIfEmptyFields = [
            "smtp_pass",
        ];

        //array of image fields
        $imgFields = [];


        $this->data["doNotSaveIfEmptyFields"] = $doNotSaveIfEmptyFields;

        if($request->method() == "POST")
        {

            $inputs = $request->except(['_token','_submit']);

            $validator = $this->_saving_validation($request);
            if ($validator !== true) return $validator;

            #region save images and files
            foreach ($imgFields as $field){
                $inputs[$field] = image::general_save_img_without_attachment($request, [
                    "old_path"         => $this->getImageOldPath($this->data["img_objs"],$field),
                    "img_file_name"    => $field,
                    "upload_file_path" => "/settings"
                ]);

                $inputs[$field] = json_encode($inputs[$field]);
            }
            #endregion


            #region save all settings

            foreach($inputs as $fieldName => $InputValue)
            {

                if(in_array($fieldName, $doNotSaveIfEmptyFields) && empty($InputValue))
                {
                    continue;
                }

                $row = $settings->where('setting_key',$fieldName)->first();
                if(is_object($row)){
                    $row->update([
                        "setting_value" => clean($InputValue)
                    ]);
                    $action_type='update';
                }
                else{
                    settings_m::create([
                        'setting_key'   => $fieldName,
                        'setting_type'  => in_array($fieldName, $imgFields) ? "file" : "text",
                        'setting_value' => $InputValue
                    ]);
                    $action_type='create';
                }

                createLog($request, [
                    'user_id'        => $this->user_id,
                    'module'         => 'settings',
                    'module_content' => json_encode($request->all()),
                    'action_url'     => url()->full(),
                    'action_type'    => $action_type,
                    'old_obj'        => $row,
                ]);

                $this->saveSettingsAtCache($fieldName,$InputValue);

            }

            #endregion


            return $this->returnMsgWithRedirection($request,"admin/settings","Settings is saved successfully");

        }
        return $this->returnView($request,"admin.subviews.settings.show");
    }


    private function _saving_validation(Request $request)
    {
        $this->data["success"]  = "";

        $rules_values           = [];
        $rules_itself           = [];
        $attrs_names            = [];

        $validator = Validator::make($rules_values, $rules_itself, $attrs_names);

        return $this->returnValidatorMsgs($validator);
    }




}
