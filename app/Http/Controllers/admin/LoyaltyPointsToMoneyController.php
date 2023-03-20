<?php

namespace App\Http\Controllers\admin;

use App\form_builder\LoyaltyPointsToMoneyBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\loyalty_points_to_money_m;
use Illuminate\Http\Request;

class LoyaltyPointsToMoneyController extends AdminBaseController
{

    use CrudTrait;

    /** @var loyalty_points_to_money_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Loyalty Points To Money");

        $this->modelClass          = loyalty_points_to_money_m::class;
        $this->viewSegment         = "loyalty_points_to_money";
        $this->routeSegment        = "loyalty_points_to_money";
        $this->builderObj          = new LoyaltyPointsToMoneyBuilder();
        $this->primaryKey          = "id";
    }

    public function index(Request $request)
    {
        havePermissionOrRedirect("admin/loyalty_points_to_money", "show_action");

        $this->data["results"] = $this->modelClass::getAllRows();
        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");
    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/loyalty_points_to_money", $item_id == null ? "add_action" : "edit_action");
    }


    public function beforeDeleteRow(Request $request)
    {
        havePermissionOrRedirect("admin/loyalty_points_to_money", "delete_action");
    }


}
