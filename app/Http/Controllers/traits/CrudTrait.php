<?php


namespace App\Http\Controllers\traits;


use App\btm_form_helpers\BTMAdminValidator;
use App\btm_form_helpers\general_save_form;
use App\form_builder\FormBuilder;
use App\models\langs_m;
use Illuminate\Http\Request;

trait CrudTrait
{

    /** @var FormBuilder */
    public $builderObj;
    public $modelClass;
    public $viewSegment;
    public $routeSegment;
    public $primaryKey;
    public $editOnly = false;

    //in case you want to redirect to show screen not to edit screen
    public $redirectAfterSave = "";

    //in case you're calling this from vendor panel
    public $fullViewPath;
    public $callingFrom = "admin";

    public $enableAutoTranslate = false;

    public function beforeDoAnythingAtSave(Request $request,$item_id){

    }

    public function beforeSaveRow(Request $request){
        return $request;
    }

    public function beforeAddNewRow(Request $request){
        return $request;
    }

    public function beforeUpdateRow(Request $request){
        return $request;
    }

    public function reviewBeforeUpdateRow(Request $request, $item_obj){
        // custom for any purpose may be occurred on old and new data before save (ex.log changes)

        return $request;
    }

    public function beforeDeleteRow(Request $request){
        return $request;
    }

    public function customValidation(Request $request,$item_id = null){
        return true;
    }

    public function getEditObj(Request $request,$item_id){
        return $this->modelClass::findOrFail($item_id);
    }

    public function afterSave(Request $request, $item_obj){

    }

    public function appendDataAtEditOperationForView(Request $request, $item_obj){
        return $item_obj;
    }

    public function save(Request $request,$item_id = null)
    {

        $this->beforeDoAnythingAtSave($request,$item_id);

        if($this->editOnly && $item_id == null){
            return abort(404);
        }

        $item_obj   = "";

        if ($item_id != null)
        {
            $item_obj = $this->getEditObj($request,$item_id);

            $item_obj =general_save_form::prepare_fields_before_show(
                $this->builderObj,
                $item_obj
            );

        }

        $this->getAllLangs();

        $item_obj = $this->appendDataAtEditOperationForView($request, $item_obj);

        $this->data["item_data"]  = $item_obj;
        $this->data["builderObj"] = $this->builderObj;

        if ($request->method()=="POST")
        {
            $validation_msgs = BTMAdminValidator::checkValidation($request,$this->builderObj,$this->data["all_langs"]);
            if($validation_msgs!==true){
                return $validation_msgs;
            }

            $validation_msgs = $this->customValidation($request,$item_id);
            if($validation_msgs!==true){
                return $validation_msgs;
            }

            $request  = $this->beforeSaveRow($request);

            $request=general_save_form::prepare_fields_before_save(
                $request,
                $this->builderObj,
                $this->data["all_langs"],
                $item_obj
            );

            // update
            if ($item_id != null){
                $request  = $this->beforeUpdateRow($request);
                $item_obj = $this->modelClass::find($item_id);

                $request  = $this->reviewBeforeUpdateRow($request, $item_obj);

                $item_obj->update($request->all());
            }
            else{
                $request  = $this->beforeAddNewRow($request);
                $item_obj = $this->modelClass::create($request->all());
            }

            $this->afterSave($request, $item_obj);
            $this->saveOtherLanguageWithTranslateFields($request, $item_obj);

            createLog($request, [
                'item_id'        => $item_id,
                'user_id'        => $this->user_id,
                'module'         => $this->modelClass,
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'old_obj'        => $this->data["item_data"],
            ]);

            return $this->afterSaveRedirectionOptions($request, $item_obj);
        }

        return $this->returnView($request,(empty($this->fullViewPath)?"admin.subviews.$this->viewSegment.save":$this->fullViewPath));
    }

    public function afterSaveRedirectionOptions(Request $request, $item_obj): array
    {

        $msg = "Saved Successfully";
        if(!empty($request->get("saved_message"))){
            $msg = $request->get("saved_message");
        }

        return [
            "msg"      => $msg,
            "redirect" =>
                empty($this->redirectAfterSave) ?
                    url("$this->callingFrom/$this->routeSegment/save/" . $item_obj->{$this->primaryKey}) :
                    $this->redirectAfterSave,
        ];
    }



    public function saveOtherLanguageWithTranslateFields(Request $request, $item_obj)
    {
        //when admin is not assigned with all languages then we should add
        // an initial value for other languages
        // this initial value could be the EN value if it is exist or it could be
        // first language entered at translated Fields

        if(!$this->enableAutoTranslate) return;

        $allLangs     = langs_m::all();

        $item_obj     = $this->modelClass::findOrFail($item_obj->{$this->primaryKey});
        $updateValues = [];

        foreach ($this->builderObj->translate_fields as $translate_field){

            $TFValue = $item_obj->{$translate_field};
            $TFValue = json_decode($TFValue);

            $TFMainLanguageValue = $TFValue->{config("default_language.main_lang_title")} ?? "";

            if(empty($TFMainLanguageValue)){
                $firstVal = (array)$TFValue;
                $firstVal = array_values($firstVal);
                if(is_array($firstVal) && count($firstVal)){
                    $TFMainLanguageValue = $firstVal[0];
                }
            }

            foreach ($allLangs as $lang){

                if(isset($TFValue->{$lang->lang_title}) && !empty($TFValue->{$lang->lang_title}))continue;

                $TFValue->{$lang->lang_title} = $TFMainLanguageValue;

            }

            $updateValues[$translate_field] = json_encode($TFValue,JSON_UNESCAPED_UNICODE);

        }

        $item_obj->update($updateValues);

    }

    public function delete(Request $request){

        $this->beforeDeleteRow($request);
        $this->general_remove_item($request,$this->modelClass);

    }

}
