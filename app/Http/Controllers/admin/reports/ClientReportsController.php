<?php

namespace App\Http\Controllers\admin\reports;

use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\Controller;
use App\models\client\clients_m;
use Illuminate\Http\Request;

class ClientReportsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
    }
    #region clientCount

    private function _returnClientCountData(Request $request, $results)
    {
        $this->data["request_data"]                      = (object)$request;
        $this->data['show_input_year']                   = false;
        $this->data['show_from_date_and_to_date_inputs'] = false;

    }

    public function clientCountYearly(Request $request)
    {

        $this->setMetaTitle("client count yearly");

        $this->data['total_client_by_branch'] = clients_m::getReportTotalClientCountByBranchYearly($request->all());
        $this->_returnClientCountData($request, $this->data['total_client_by_branch']);


        $this->data['show_input_year'] = true;
        $this->data['report_type_date'] ='Yearly';


        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.client_count.client_count");

    }

    public function clientCountMonthly(Request $request)
    {

        $this->setMetaTitle("client count yearly");

        $this->data['total_client_by_branch'] = clients_m::getReportTotalClientCountByBranchMonthly($request->all());
        $this->_returnClientCountData($request, $this->data['total_client_by_branch']);
        $this->data['show_input_year'] = true;
        $this->data['report_type_date'] ='Monthly';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.client_count.client_count");

    }

    public function clientCount(Request $request)
    {

        $this->setMetaTitle("client count yearly");

        $this->data['total_client_by_branch'] = clients_m::getReportTotalClientCountByBranch($request->all());
        $this->_returnClientCountData($request, $this->data['total_client_by_branch']);
        $this->data['show_from_date_and_to_date_inputs'] = true;
        $this->data['report_type_date'] ='From,To Date';

        return $this->returnView($request, "admin.subviews.reports.client_orders_reports.client_count.client_count");

    }

    #endregion

}
