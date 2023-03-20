<?php

namespace App\Http\Controllers\admin;

use App\form_builder\LanguagesBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\langs_m;
use Illuminate\Http\Request;

class LanguagesController extends AdminBaseController
{
    use CrudTrait;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Languages");

        $this->modelClass        = langs_m::class;
        $this->viewSegment       = "langs";
        $this->routeSegment      = "langs";
        $this->builderObj        = new LanguagesBuilder();
        $this->primaryKey        = "lang_id";
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/languages","show_action");

        $this->data["show_all_langs"] = langs_m::orderBy("lang_is_default","desc")->get();

        return $this->returnView($request,"admin.subviews.langs.show");

    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {

        havePermissionOrRedirect("admin/languages",$item_id==null?"add_action":"edit_action");

        if ($item_id == 1)
        {
            // to not allowed hide default language
            $request["lang_is_active"] = 1;
        }

    }

    public function customValidation(Request $request, $item_id = null)
    {

        $rules_values=[
            "lang_title" => $request["lang_title"],
        ];

        $rules_itself=[
            "lang_title" => "required|unique:langs,lang_title,".$item_id.",lang_id,deleted_at,NULL"
        ];


        $validator = \Validator::make($rules_values,$rules_itself);

        return $this->returnValidatorMsgs($validator);

    }

    public function afterSave(Request $request, $item_obj){

        \Cache::forget('all_langs');
        \Cache::delete('all_langs');

    }

    public function delete(Request $request)
    {

        havePermissionOrRedirect("admin/languages","delete_action");

        $langObj = langs_m::findOrFail($request->get("item_id"));
        if($langObj->lang_is_default){
            return [
                "error" => "can not delete the default lang"
            ];
        }

        $this->general_remove_item($request,$this->modelClass);

    }


}
