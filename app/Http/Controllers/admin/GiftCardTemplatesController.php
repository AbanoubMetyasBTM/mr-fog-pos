<?php

namespace App\Http\Controllers\admin;

use App\form_builder\GiftCardTemplatesBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\gift_card\gift_card_templates_m;
use App\models\gift_card\gift_cards_m;
use Illuminate\Http\Request;

class GiftCardTemplatesController extends AdminBaseController
{

    use CrudTrait;

    /** @var gift_card_templates_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Gift Card Templates");

        $this->modelClass   = gift_card_templates_m::class;
        $this->viewSegment  = "gift_card_templates";
        $this->routeSegment = "gift-card-templates";
        $this->builderObj   = new GiftCardTemplatesBuilder();
        $this->primaryKey   = "template_id";
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/gift_card_templates", "show_action");

        $this->data["results"] = $this->modelClass::getAllGiftCardsTemplatesForAdminPanel();

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");

    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {

        havePermissionOrRedirect("admin/gift_card_templates", $item_id == null ? "add_action" : "edit_action");

    }

    public function delete(Request $request)
    {

        havePermissionOrRedirect("admin/gift_card_templates", "delete_action");

        $checkRows = gift_cards_m::checkIfTemplateIsUsed($request->get("item_id"));
        if ($checkRows) {
            echo json_encode([
                "msg" => "
                    you cannot remove a used template,
                    you can make it not active so it won't be used anymore any future gift cards
                "
            ]);
            die("");
        }

        $this->general_remove_item($request, $this->modelClass);

    }


}
