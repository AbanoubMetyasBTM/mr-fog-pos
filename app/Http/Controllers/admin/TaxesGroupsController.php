<?php

namespace App\Http\Controllers\admin;

use App\form_builder\TaxesGroupsBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branches_m;
use App\models\client\clients_m;
use App\models\taxes_groups_m;
use Illuminate\Http\Request;

class TaxesGroupsController extends AdminBaseController
{
    use CrudTrait;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("taxes groups");

        $this->modelClass        = taxes_groups_m::class;
        $this->viewSegment       = "taxes_groups";
        $this->routeSegment      = "taxes-groups";
        $this->builderObj        = new TaxesGroupsBuilder();
        $this->primaryKey        = "group_id";
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/taxes_groups","show_action");

        $this->data["results"] = taxes_groups_m::getAllTaxesGroups();

        return $this->returnView($request,"admin.subviews.taxes_groups.show");

    }

    public function delete(Request $request)
    {

        havePermissionOrRedirect("admin/taxes_groups","delete_action");

        $item_id = (int)$request->get("item_id");

        $client=clients_m::getClientByGroupId($item_id);
        if ($client){
            return json_encode([
                "msg" => "You cannot delete these taxes groups because the clients use this"
            ]);
        }

        $branch=branches_m::getBranchByGroupId($item_id);
        if ($branch){
            return json_encode([
                "msg" => "You cannot delete these taxes groups because the branches use this"
            ]);
        }

        $this->general_remove_item($request,$this->modelClass);

    }




}
