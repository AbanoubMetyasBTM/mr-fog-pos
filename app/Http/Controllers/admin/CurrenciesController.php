<?php

namespace App\Http\Controllers\admin;

use App\Adapters\API\CurrenciesRates\ICurrenciesRatesAdapter;
use App\Adapters\API\CurrenciesRates\Implementation\CurrenciesRatesAdapter;
use App\Adapters\DB\Implementation\CurrenciesAdapter;
use App\form_builder\CurrenciesBuilder;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\CrudTrait;
use App\models\branch\branches_m;
use App\models\currencies_m;
use App\models\langs_m;
use App\Transformers\StaticData\CurrenciesTransformer;
use Illuminate\Http\Request;

class CurrenciesController extends AdminBaseController
{

    use CrudTrait;

    /** @var currencies_m */
    public $modelClass;

    public function __construct()
    {
        parent::__construct();

        $this->setMetaTitle("Currencies");

        $this->modelClass   = currencies_m::class;
        $this->viewSegment  = "currencies";
        $this->routeSegment = "currencies";
        $this->builderObj   = new CurrenciesBuilder();
        $this->primaryKey   = "currency_id";
        $this->editOnly     = false;

    }

    public function index(Request $request)
    {

        havePermissionOrRedirect("admin/currencies", "show_action");

        $this->data["results"] = $this->modelClass::getAllCurrencies();

        return $this->returnView($request, "admin.subviews.$this->viewSegment.show");
    }

    public function beforeDoAnythingAtSave(Request $request, $item_id)
    {
        havePermissionOrRedirect("admin/currencies", $item_id == null ? "add_action" : "edit_action");
    }

    public function customValidation(Request $request, $item_id = null)
    {

        $rules_values = [
            "currency_code" => $request["currency_code"],
        ];

        $rules_itself = [
            "currency_code" => "required|unique:currencies,currency_code," . $item_id . ",currency_id,deleted_at,NULL"
        ];

        $validator = \Validator::make($rules_values, $rules_itself);

        return $this->returnValidatorMsgs($validator);

    }

    public function beforeSaveRow(Request $request)
    {

        $getCurrency = strtolower($request->get('currency_code'));
        if ($getCurrency == config("default_currency.value")) {
            $request["currency_rate"]                 = 1;
            $request["currency_is_active"]            = 1;
            $request["currency_is_updated_automatic"] = 0;
        }

        return $request;
    }

    public function afterSave(Request $request, $item_obj)
    {

        \Cache::forget('currencies_values_at_this_day');
        updateCurrenciesRates();

    }


    //in case we want to import all currencies
    public function importAllCurrencies()
    {

        $allLangs = langs_m::all();


        $currenciesRatesAdapter = app()->make(ICurrenciesRatesAdapter::class);

        $oldCurrencies = currencies_m::getAllCurrencies()->pluck("currency_code", "currency_code")->all();

        $allCurrencies = $currenciesRatesAdapter->fetchAllCurrenciesNames();

        $allCurrencies = array_diff_key($allCurrencies, $oldCurrencies);

        $insertNewRows = [];
        foreach ($allCurrencies as $currencyCode => $currencyName) {

            $currencyNameLangs = new \stdClass();

            foreach ($allLangs as $lang) {

                $currencyNameLangs->{$lang->lang_title} = $currencyName;

            }

            $insertNewRows[] = [
                'currency_name'                 => json_encode($currencyNameLangs, JSON_UNESCAPED_UNICODE),
                'currency_code'                 => $currencyCode,
                'currency_is_updated_automatic' => "1"
            ];
        }

        if (isset_and_array($insertNewRows)) {
            currencies_m::insert($insertNewRows);
        }

        updateCurrenciesRates();

    }

    public function delete(Request $request)
    {

        havePermissionOrRedirect("admin/currencies", "delete_action");

        $itemObj     = currencies_m::findOrFail($request->get("item_id"));
        $branchExist = branches_m::getAllBranches()->where("branch_currency", $itemObj->currency_code)->count();

        if ($branchExist > 0) {
            return json_encode([
                "msg" => "you can not delete this currency because another branch used it"
            ]);
        }

        $this->general_remove_item($request, $this->modelClass);

    }

}
