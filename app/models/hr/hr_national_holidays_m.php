<?php

namespace App\models\hr;

use App\form_builder\ProductPromotionsBuilder;
use App\form_builder\ProductsBuilder;
use App\models\branch\branches_m;
use App\models\ModelUtilities;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class hr_national_holidays_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "hr_national_holidays";

    protected $primaryKey = "id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'country_name', 'holiday_title', 'holiday_date'
    ];

    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            hr_national_holidays.*
       "));

        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getAllNationalHolidays(array $attrs = []): Collection
    {
        if (isset($attrs['current_year']) && !empty($attrs['current_year'])){
            $attrs['free_conds'][] = "
               hr_national_holidays.holiday_date >= '{$attrs['current_year']}'
            ";
        }

        if (isset($attrs['country']) && !empty($attrs['country'])){
            $attrs['free_conds'][] = "
               hr_national_holidays.country_name like '{$attrs['country']}'
            ";
        }

        return self::getData($attrs);

    }


    public static function getNationalHolidaysOfBranch($branchId): Collection
    {
        $branchObj   = branches_m::getBranchById($branchId);
        $currentYear = Carbon::now()->startOfYear();
        $country     = $branchObj->branch_country;

        return self::getData([
            "free_conds" => [
                " hr_national_holidays.holiday_date >= '{$currentYear}'",
                "hr_national_holidays.country_name like '{$country}'"
            ],
        ]);

    }




}
