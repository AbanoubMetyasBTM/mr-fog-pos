<?php

namespace App\Http\Controllers\admin;

use App\form_builder\PagesBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\pages_m;
use Illuminate\Http\Request;

class PagesController extends AdminBaseController
{

    use CrudTrait;

    /** @var pages_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("pages");

        $this->modelClass          = pages_m::class;
        $this->viewSegment         = "pages";
        $this->routeSegment        = "site-pages";
        $this->builderObj          = new PagesBuilder();
        $this->primaryKey          = "page_id";
        $this->enableAutoTranslate = true;
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/pages", "show_action");

        $cond   = [];
        $cond[] = ["pages.lang_id", "=", $this->data["admin_selected_lang_id"]];
        $cond[] = ["pages.page_type", "=", "default"];

        $this->data["results"] = $this->modelClass::getData([
            "cond"     => $cond,
            "order_by" => ["page_order", "asc"],
        ]);

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");
    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {

        havePermissionOrRedirect("admin/pages", $item_id == null ? "add_action" : "edit_action");

    }

    public function beforeSaveRow(Request $request)
    {

        $request["page_slug"] = string_safe($request["page_slug"]);

        return $request;

    }

    public function beforeAddNewRow(Request $request)
    {

        $request["page_type"]  = "default";
        $request["page_order"] = pages_m::getPagesCount($this->data["admin_selected_lang_id"]);
        $request["lang_id"]    = $this->data["admin_selected_lang_id"];

        return $request;
    }

    public function delete(Request $request)
    {

        havePermissionOrRedirect("admin/pages", "delete_action");
        $this->general_remove_item($request, $this->modelClass);

    }

}
