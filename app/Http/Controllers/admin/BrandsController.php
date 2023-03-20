<?php

namespace App\Http\Controllers\admin;

use App\form_builder\BrandsBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\brands_m;
use Illuminate\Http\Request;

class BrandsController extends AdminBaseController
{

    use CrudTrait;

    /** @var brands_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Brands");

        $this->modelClass          = brands_m::class;
        $this->viewSegment         = "brands";
        $this->routeSegment        = "brands";
        $this->builderObj          = new BrandsBuilder();
        $this->primaryKey          = "brand_id";
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/brands", "show_action");

        $this->data["results"] = $this->modelClass::getAllBrands();

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");

    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {

        havePermissionOrRedirect("admin/brands", $item_id == null ? "add_action" : "edit_action");

    }


    public function beforeDeleteRow(Request $request)
    {

        havePermissionOrRedirect("admin/brands", "delete_action");

    }


}
