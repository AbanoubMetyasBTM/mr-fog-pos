<?php

namespace App\Http\Controllers\admin;

use App\form_builder\BranchesBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branches_m;
use App\models\client\clients_orders_m;
use App\models\employee\employee_details_m;
use App\models\supplier\suppliers_orders_m;
use App\User;
use App\models\wallets_m;
use Illuminate\Http\Request;

class BranchesController extends AdminBaseController
{

    use CrudTrait;

    /** @var branches_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Branches");

        $this->modelClass          = branches_m::class;
        $this->viewSegment         = "branches";
        $this->routeSegment        = "branches";
        $this->builderObj          = new BranchesBuilder();
        $this->primaryKey          = "branch_id";
    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/branches", "show_action");

        $this->data["results"] = $this->modelClass::getAllBranchesOrCurrentBranchOnly([
            "need_wallet_join" => "yes"
        ]);

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");

    }

    public function accessBranchAsBranchAdmin(Request $request, $branchId)
    {

        if (\Auth::user()->user_role != "admin") {
            return abort(404);
        }

        $employees = employee_details_m::getEmployeesOrOrSpecificBranch([
            "branch_id" => $branchId,
            "user_role" => "branch_admin"
        ]);

        if($employees->count()==0){
            return $this->returnMsgWithRedirection(
                $request,
                "admin/branches",
                "this branch does not have branch admin"
            );
        }

        $request->session()->put("login_to_branch_id", $branchId);
        $request->session()->put("login_to_branch_id_as_user_id", $employees->first()->user_id);

        return redirect()->to("admin/dashboard")->send();

    }

    public function customValidation(Request $request, $item_id = null)
    {


        $rules_values = [
            "tax_group_id" => $request["tax_group_id"],
        ];

        $rules_itself = [
            "tax_group_id" => "required"
        ];

        $validator = \Validator::make($rules_values, $rules_itself);
        return $this->returnValidatorMsgs($validator);


    }

    public function backToMainAdminPanel(Request $request)
    {

        if (\Auth::user()->user_role!="admin"){
            return abort(404);
        }

        $request->session()->forget("login_to_branch_id");
        $request->session()->forget("login_to_branch_id_as_user_id");

        return redirect()->to("admin/dashboard")->send();

    }


    #region save

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/branches", $item_id == null ? "add_action" : "edit_action");

        if ($this->branch_id!=null && $this->branch_id!=$item_id){
            return abort(404);
        }

        $this->builderObj          = new BranchesBuilder($item_id);

    }

    public function beforeAddNewRow(Request $request)
    {
        $amount = 0;
        $cash_wallet = wallets_m::saveWallet($amount);
        $debit_card_wallet = wallets_m::saveWallet($amount);
        $credit_card_wallet = wallets_m::saveWallet($amount);
        $cheque_wallet_wallet = wallets_m::saveWallet($amount);


        $request->request->add(
        [
            'cash_wallet_id'        => $cash_wallet->wallet_id,
            'debit_card_wallet_id'  => $debit_card_wallet->wallet_id,
            'credit_card_wallet_id' => $credit_card_wallet->wallet_id,
            'cheque_wallet_id'      => $cheque_wallet_wallet->wallet_id
        ]);
        return $request;
    }


    public function afterSave(Request $request, $item_obj)
    {
        branches_m::removeBranchFromCache($item_obj->branch_id);
    }

    #endregion

    public function beforeDeleteRow(Request $request)
    {

        havePermissionOrRedirect("admin/branches", "delete_action");

    }

    public function delete(Request $request){

        $this->beforeDeleteRow($request);


        $branch_id                     = (int)$request->get("item_id");
        $IsSetClientsOrdersForBranch   = clients_orders_m::checkIfBranchHasOrders($branch_id);
        $IsSetSuppliersOrdersForBranch = suppliers_orders_m::checkIfBranchHasOrders($branch_id);
        $IsSetEmployeesForBranch       = User::checkIfBranchHasUsersWithType($branch_id, 'employee');

        if ($IsSetClientsOrdersForBranch == false && $IsSetSuppliersOrdersForBranch == false && $IsSetEmployeesForBranch == false){

            $this->general_remove_item($request, $this->modelClass);
        }
        else{
            $output["msg"] = "can not delete this branch because it contains orders or employees";
            return json_encode($output);
        }

    }

}
