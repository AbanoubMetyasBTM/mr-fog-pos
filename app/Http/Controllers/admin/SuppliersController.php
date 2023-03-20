<?php

namespace App\Http\Controllers\admin;

use App\form_builder\SuppliersBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\supplier\suppliers_m;
use App\models\supplier\suppliers_orders_m;
use App\models\transactions_log_m;
use App\models\wallets_m;
use Illuminate\Http\Request;

class SuppliersController extends AdminBaseController
{

    use CrudTrait;

    /** @var suppliers_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("suppliers");

        $this->modelClass   = suppliers_m::class;
        $this->viewSegment  = "suppliers";
        $this->routeSegment = "suppliers";
        $this->builderObj   = new SuppliersBuilder();
        $this->primaryKey   = "sup_id";
    }

    public function index(Request $request)
    {
        havePermissionOrRedirect("admin/suppliers", "show_action");

        $this->data["results"] = $this->modelClass::getAllSuppliers([
            "need_wallet_join" => true,
            "paginate"         => 50,
            'request_data'     => $request->all(),
        ]);

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");
    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {

        havePermissionOrRedirect("admin/suppliers", $item_id == null ? "add_action" : "edit_action");

        $this->builderObj = new SuppliersBuilder($item_id);

    }

    public function beforeAddNewRow(Request $request)
    {

        $wallet = wallets_m::saveWallet(0);
        $request->request->add(['wallet_id' => $wallet->wallet_id]);
        return $request;
    }

    public function customValidation(Request $request, $item_id = null)
    {

        $rules_values = [
            "sup_phone" => $request["sup_phone"],
        ];

        $rules_itself = [
            "sup_phone" => "required|unique:suppliers,sup_phone,".$item_id.",sup_id,deleted_at,NULL"
        ];

        $validator = \Validator::make($rules_values, $rules_itself);

        return $this->returnValidatorMsgs($validator);

    }


    public function beforeDeleteRow(Request $request)
    {
        havePermissionOrRedirect("admin/suppliers", "delete_action");
    }


    public function delete(Request $request)
    {

        $this->beforeDeleteRow($request);

        $supplier_id                    = (int)$request->get("item_id");
        $IsSetOrdersForSupplier         = suppliers_orders_m::checkIfSupplierHasOrders($supplier_id);
        $supplierData                   = suppliers_m::getSupplierDataById($supplier_id);
        $IsSetTransactionLogForSupplier = transactions_log_m::checkIfTransactionsLogHaveSpecificWallet($supplierData->wallet_id);

        if ($IsSetOrdersForSupplier == false && $IsSetTransactionLogForSupplier == false) {

            $this->general_remove_item($request, $this->modelClass);
        }
        else {
            $output["msg"] = "can not delete this supplier because he has orders";
            return json_encode($output);
        }

    }


}
