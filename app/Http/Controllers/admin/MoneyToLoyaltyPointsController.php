<?php

namespace App\Http\Controllers\admin;

use App\form_builder\MoneyToLoyaltyPointsBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\money_to_loyalty_points_m;
use Illuminate\Http\Request;

class MoneyToLoyaltyPointsController extends AdminBaseController
{

    use CrudTrait;

    /** @var money_to_loyalty_points_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Money To Loyalty Points");

        $this->modelClass          = money_to_loyalty_points_m::class;
        $this->viewSegment         = "money_to_loyalty_points";
        $this->routeSegment        = "money_to_loyalty_points";
        $this->builderObj          = new MoneyToLoyaltyPointsBuilder();
        $this->primaryKey          = "id";
    }

    public function index(Request $request)
    {
        havePermissionOrRedirect("admin/money_to_loyalty_points", "show_action");

        $this->data["results"] = $this->modelClass::getAllMoneyToLoyaltyPoints();
        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");
    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/money_to_loyalty_points", $item_id == null ? "add_action" : "edit_action");
    }


    public function beforeDeleteRow(Request $request)
    {
        havePermissionOrRedirect("admin/money_to_loyalty_points", "delete_action");
    }


}
