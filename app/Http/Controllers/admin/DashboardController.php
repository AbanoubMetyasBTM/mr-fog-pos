<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\AdminBaseController;
use App\models\branch\branch_inventory_m;
use App\models\branch\branches_m;
use App\models\brands_m;
use App\models\categories_m;
use App\models\client\clients_m;
use App\models\client\clients_orders_m;
use App\models\gift_card\gift_cards_m;
use App\models\inventory\inventories_m;
use App\models\inventory\inventories_products_m;
use App\models\money_installments_m;
use App\models\permissions\permissions_m;
use App\models\register\registers_m;
use App\models\supplier\suppliers_m;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    #region index

    private function clientOrdersStatistics($dashboard_data)
    {

        $dashboard_data['clientOrdersCountThisYear'] = $this->clientOrdersCount([
            "branch_id"     => $this->branch_id,
            'selected_year' => date('Y'),
        ]);

        $dashboard_data['clientOrdersCountThisMonth'] = $this->clientOrdersCount([
            "branch_id"      => $this->branch_id,
            'selected_year'  => date('Y'),
            'selected_month' => date('m'),
        ]);

        $dashboard_data['clientOrdersCountThisDay'] = $this->clientOrdersCount([
            "branch_id" => $this->branch_id,
            'date_from' => date("Y-m-d"),
            'date_to'   => date("Y-m-d"),
        ]);

        $dashboard_data['allClientOrdersCount'] = $this->clientOrdersCount([
            "branch_id" => $this->branch_id,
        ]);

        return $dashboard_data;

    }

    private function clientsStatistics($dashboard_data)
    {

        $dashboard_data['countClientsThisYear']  = $this->countClients([
            "branch_id"     => $this->branch_id,
            'selected_year' => date('Y'),
        ]);
        $dashboard_data['countClientsThisMonth'] = $this->countClients([
            "branch_id"      => $this->branch_id,
            'selected_year'  => date('Y'),
            'selected_month' => date('m'),
        ]);
        $dashboard_data['countClientsThisDay']   = $this->countClients([
            "branch_id" => $this->branch_id,
            'date_from' => date("Y-m-d"),
            'date_to'   => date("Y-m-d"),
        ]);
        $dashboard_data['allCountClients']       = $this->countClients([
            "branch_id" => $this->branch_id,
        ]);

        return $dashboard_data;

    }

    private function giftCardsStatistics($dashboard_data)
    {

        $dashboard_data['countCardsThisYear']  = $this->countCards([
            "branch_id"     => $this->branch_id,
            'selected_year' => date('Y')
        ]);
        $dashboard_data['countCardsThisMonth'] = $this->countCards([
            "branch_id"      => $this->branch_id,
            'selected_year'  => date('Y'),
            'selected_month' => date('m')
        ]);
        $dashboard_data['countCardsThisDay']   = $this->countCards([
            "branch_id" => $this->branch_id,
            'date_from' => date("Y-m-d"),
            'date_to'   => date("Y-m-d")
        ]);
        $dashboard_data['allCountCards']       = $this->countCards([
            "branch_id" => $this->branch_id,
        ]);

        return $dashboard_data;

    }

    private function balancesStatistics($dashboard_data)
    {

        $dashboard_data['giftCardsTotalWalletAmountGroupedByCurrency'] = gift_cards_m::totalGiftCardsWalletAmount($this->branch_id);
        $dashboard_data['clientsTotalWalletAmountGroupedByCurrency']   = clients_m::totalClientWalletAmount($this->branch_id);

        if ($this->branch_id == null) {
            $dashboard_data['suppliersTotalWalletAmountGroupedByCurrency'] = suppliers_m::totalSupplierWalletAmount();
        }

        return $dashboard_data;

    }

    private function shouldDoAlertsForMainAdmin($dashboard_data)
    {

        $dashboard_data['category_has_at_least_one']  = categories_m::checkItHasAtLeastOneRow();
        $dashboard_data['brand_has_at_least_one']     = brands_m::checkItHasAtLeastOneRow();
        $dashboard_data['inventory_has_at_least_one'] = inventories_m::checkItHasAtLeastOneRow();
        $dashboard_data['branch_has_at_least_one']    = branches_m::checkItHasAtLeastOneRow();

        return $dashboard_data;

    }

    private function shouldDoAlertsForBranchAdmin($dashboard_data)
    {

        $dashboard_data['has_main_inventory_or_not']  = branch_inventory_m::checkItHasMainInventory($this->branch_id);
        $dashboard_data['inventories_has_products_or_not']     = inventories_products_m::checkItHasAtLeastOneRow();
        $dashboard_data['has_registers_or_not'] = registers_m::checkItHasAtLeastOneRow($this->branch_id);

        return $dashboard_data;

    }

    private function index_admin(Request $request)
    {

        $dashboard_data = [];
        $dashboard_data = $this->clientOrdersStatistics($dashboard_data);
        $dashboard_data = $this->clientsStatistics($dashboard_data);
        $dashboard_data = $this->giftCardsStatistics($dashboard_data);
        $dashboard_data = $this->balancesStatistics($dashboard_data);
        $dashboard_data = $this->shouldDoAlertsForMainAdmin($dashboard_data);


        $dashboard_data['limitOrdersGroupedByBranches'] = clients_orders_m::getOrdersGroupedByBranches([
            'date_from' => date("Y-m-d"),
            'date_to'   => date("Y-m-d")
        ]);

        $dashboard_data["thisDateLastTenOrders"] = clients_orders_m::getAllClientOrders(null, [
            "limit"    => 10,
            "order_by" => ["client_orders.client_order_id", "desc"]
        ]);

        $dashboard_data["criticalQuantities"] = inventories_products_m::getCriticalQuantities([
            "limit"    => 10
        ]);

        $dashboard_data["getMoneyDebt"] = money_installments_m::getMoneyDebt([
            "limit" => 10
        ]);

        $dashboard_data["getMoneyOwed"] = money_installments_m::getMoneyOwed([
            "limit" => 10
        ]);

        return $dashboard_data;

    }

    private function index_branch_admin(Request $request)
    {

        $dashboard_data = [];
        $dashboard_data = $this->shouldDoAlertsForBranchAdmin($dashboard_data);
        $dashboard_data = $this->clientOrdersStatistics($dashboard_data);
        $dashboard_data = $this->clientsStatistics($dashboard_data);
        $dashboard_data = $this->giftCardsStatistics($dashboard_data);
        $dashboard_data = $this->balancesStatistics($dashboard_data);
        $dashboard_data = $this->shouldDoAlertsForMainAdmin($dashboard_data);


        $dashboard_data["thisDateLastTenOrders"] = clients_orders_m::getAllClientOrders(null, [
            "branch_id" => $this->branch_id,
            "limit"     => 10,
            "order_by"  => ["client_orders.client_order_id", "desc"]
        ]);

        $dashboard_data["criticalQuantities"] = inventories_products_m::getCriticalQuantities([
            "branch_id" => $this->branch_id,
            "limit"     => 10
        ]);


        return $dashboard_data;

    }

    private function index_employee(Request $request)
    {
        return [];
    }


    private function clientOrdersCount($attrs=[]){
       return clients_orders_m::getClientOrdersCount($attrs)->total_count;
    }

    private function countClients($attrs=[]){
        return clients_m::getCountClients($attrs)->total_clients;
    }

    private function countCards($attrs=[]){
       return gift_cards_m::getCountCards($attrs)->total_gift_cards;
    }

    private function reCachePermissions()
    {
        $Permissions = \Cache::get('user_permissions_' . $this->user_id);

        if($Permissions == null){
            \Cache::forever(
                'user_permissions_' . $this->user_id,
                permissions_m::where("user_id", $this->user_id)->get()->groupBy("page_name")
            );
        }
    }

    public function index(Request $request)
    {
        $this->setMetaTitle("Dashboard");

        $this->reCachePermissions();

        $dashbaord_cached_data = $this->{"index_" . $this->current_user_data->user_role}($request);
        $this->data            = array_merge($this->data, $dashbaord_cached_data);

        return $this->returnView($request,"admin.subviews.dashboard.index_".$this->current_user_data->user_role);
    }

    #endregion

    public function selectLanguage(Request $request){

        \Session::put('admin_select_lang_id',$request->get("selected_lang_id"));

        return redirect()->back();

    }


    public function menuSales(Request $request)
    {
        $this->setMetaTitle("Sales");

        return $this->returnView($request, "admin.subviews.menu.sales");
    }

    public function menuInventory(Request $request)
    {
        $this->setMetaTitle("Inventory");

        return $this->returnView($request, "admin.subviews.menu.inventory");
    }

    public function menuCustomers(Request $request)
    {
        $this->setMetaTitle("Customers");

        return $this->returnView($request, "admin.subviews.menu.customers");
    }

    public function menuReports(Request $request)
    {
        $this->setMetaTitle("Reports");

        return $this->returnView($request, "admin.subviews.menu.reports");
    }

    public function menuSettings(Request $request)
    {
        $this->setMetaTitle("Settings");

        return $this->returnView($request, "admin.subviews.menu.settings");
    }


}
