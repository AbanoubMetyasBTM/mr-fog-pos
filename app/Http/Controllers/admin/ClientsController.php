<?php

namespace App\Http\Controllers\admin;

use App\form_builder\ClientsBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\client\clients_m;
use App\models\client\clients_orders_m;
use App\models\transactions_log_m;
use App\models\wallets_m;
use Illuminate\Http\Request;

class ClientsController extends AdminBaseController
{

    use CrudTrait;

    /** @var clients_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("clients");

        $this->modelClass          = clients_m::class;
        $this->viewSegment         = "clients";
        $this->routeSegment        = "clients";
        $this->builderObj          = new ClientsBuilder();
        $this->primaryKey          = "client_id";
    }

    public function index(Request $request)
    {
        havePermissionOrRedirect("admin/clients", "show_action");

        $this->data["request_data"] = (object)$request->all();

        $this->data["results"] = $this->modelClass::getAllClientsOrSpecificBranch([
            "need_wallet_join" => true,
            "need_branch_join" => true,
            "paginate"         => 50,
            'request_data'     => $request->all(),
        ]);

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");
    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/clients", $item_id == null ? "add_action" : "edit_action");
    }

    public function customValidation(Request $request, $item_id = null)
    {

        $rules_values = [
            "tax_group_id" => $request["tax_group_id"],
            "client_phone" => $request["client_phone"],
        ];

        $rules_itself = [
            "tax_group_id" => "required",
            "client_phone" => "required|unique:clients,client_phone,".$item_id.",client_id,deleted_at,NULL"
        ];

        $validator = \Validator::make($rules_values, $rules_itself);

        return $this->returnValidatorMsgs($validator);

    }

    public function getEditObj(Request $request, $item_id)
    {
        $itemObj = clients_m::findOrFail($item_id);
        if ($this->branch_id!=null && $itemObj->branch_id!=$this->branch_id){
            abort(404);
            die();
        }

        return $itemObj;
    }

    public function beforeAddNewRow(Request $request){
        $wallet       = wallets_m::saveWallet(0);
        $pointsWallet = wallets_m::saveWallet(0);

        $request["wallet_id"]        = $wallet->wallet_id;
        $request["points_wallet_id"] = $pointsWallet->wallet_id;

        if ($this->branch_id != null) {
            $request["branch_id"] = $this->branch_id;
        }

        return $request;
    }

    public function reviewBeforeUpdateRow(Request $request, $item_obj)
    {
        if (empty($item_obj->wallet_id)) {
            $wallet               = wallets_m::saveWallet(0);
            $request["wallet_id"] = $wallet->wallet_id;
        }

        if (empty($item_obj->points_wallet_id)) {
            $wallet                      = wallets_m::saveWallet(0);
            $request["points_wallet_id"] = $wallet->wallet_id;
        }

        return $request;
    }


    public function beforeDeleteRow(Request $request)
    {
        havePermissionOrRedirect("admin/clients", "delete_action");
        $item_id = $request->get('item_id');
        $itemObj = clients_m::findOrFail($item_id);
        if ($this->branch_id!=null && $itemObj->branch_id!=$this->branch_id){
            abort(404);
            die();
        }
    }


    public function delete(Request $request)
    {
        $this->beforeDeleteRow($request);
        $client_id                    = (int)$request->get("item_id");
        $IsSetOrdersForClient         = clients_orders_m::checkIfClientHasOrders($client_id);

        $clientData                   = clients_m::getClientDataById($client_id);
        $IsSetTransactionLogForClient = transactions_log_m::checkIfTransactionsLogHaveSpecificWallet($clientData->wallet_id);

        if (!($IsSetOrdersForClient == false && $IsSetTransactionLogForClient == false)){

            return json_encode([
                'msg' => "can not delete this client because he has orders",
            ]);

        }

        $this->general_remove_item($request, $this->modelClass);
    }


    public function getClientByNameOrPhone(Request $request)
    {
        $clients                = collect(clients_m::getClientByNameOrPhone($request->get("q")));
        $result["totalRecords"] = count($clients);

        if (!empty($request->get("show_all"))){
            $result["results"][] = [
                "id"                  => "",
                "display_text"        => "all",
                "client_type"         => "",
                "wallet_amount"       => "",
                "points_wallet_value" => "",
                "group_taxes"         => "",
            ];
        }

        foreach ($clients as $key => $client) {
            $result["results"][] = [
                "id"                  => $client['client_id'],
                "display_text"        => $client['client_name'],
                "client_type"         => $client['client_type'],
                "wallet_amount"       => $client['wallet_amount'],
                "points_wallet_value" => $client['points_wallet_value'],
                "group_taxes"         => json_decode($client['group_taxes']),
            ];
        }

        return json_encode($result);
    }

}
