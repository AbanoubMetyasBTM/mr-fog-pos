<?php

namespace App\Http\Controllers\admin\employee_hr;

use App\form_builder\NationalHolidaysBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branches_m;
use App\models\hr\hr_national_holidays_m;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NationalHolidaysController extends AdminBaseController
{

    use CrudTrait;

    /** @var hr_national_holidays_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("National Holidays");

        $this->modelClass          = hr_national_holidays_m::class;
        $this->viewSegment         = "national_holidays";
        $this->routeSegment        = "national-holidays";
        $this->builderObj          = new NationalHolidaysBuilder();
        $this->primaryKey          = "id";

    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/my_hr_national_holidays", "show_action");


        if (is_null($this->branch_id))
        {
            abort(404);
        }

        $branchObj             = branches_m::getBranchById($this->branch_id);
        $conds['current_year'] = Carbon::now()->startOfYear();
        $conds['country']      = $branchObj->branch_country;
        $nationalHolidays      = $this->modelClass::getAllNationalHolidays($conds);



        $this->data["results"] = $nationalHolidays;

        return $this->returnView($request, "admin.subviews.employee_hr.subviews.$this->viewSegment.show");

    }




}
