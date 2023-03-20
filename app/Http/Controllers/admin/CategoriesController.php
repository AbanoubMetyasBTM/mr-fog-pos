<?php

namespace App\Http\Controllers\admin;

use App\form_builder\CategoriesBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\categories_m;
use App\models\pages_m;
use Illuminate\Http\Request;

class CategoriesController extends AdminBaseController
{

    use CrudTrait;

    /** @var categories_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Categories");

        $this->modelClass          = categories_m::class;
        $this->viewSegment         = "categories";
        $this->routeSegment        = "categories";
        $this->builderObj          = new CategoriesBuilder();
        $this->primaryKey          = "cat_id";
        $this->enableAutoTranslate = true;
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/categories", "show_action");

        $this->data["results"] = $this->modelClass::getAllCatsWithParents();

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");

    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {

        havePermissionOrRedirect("admin/categories", $item_id == null ? "add_action" : "edit_action");

    }


    public function beforeDeleteRow(Request $request)
    {

        havePermissionOrRedirect("admin/categories", "delete_action");

    }


}
