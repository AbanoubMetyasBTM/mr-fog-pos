<?php

namespace App\Jobs\supplierOrders;

use App\btm_form_helpers\AddSupplierOrderHelper;
use App\models\supplier\supplier_order_items_m;
use App\models\supplier\suppliers_orders_m;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use function React\Promise\all;

class AddSupplierOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;


    public array $attrs;

    public function __construct(array $attrs = [])
    {
        /*
            $attrs = [
                "requestData" => ""
            ];
        */

        $this->attrs = $attrs;
    }


    public function handle()
    {

        /**
         * @var Request $request
         */

        $requestData = $this->attrs['requestData'];

        $request = Request::capture();
        $request = $request->merge($requestData);

        \DB::beginTransaction();

        $orderObj       = suppliers_orders_m::create($request->all());
        $orderItemsData = AddSupplierOrderHelper::orderItemsHandler($request, $orderObj->supplier_order_id);
        $orderItemsData = collect($orderItemsData);

        supplier_order_items_m::insert($orderItemsData->all());

        if ($request->get("order_status") == "done") {
            AddSupplierOrderHelper::afterAddDoneSupplierOrder(
                $request,
                $orderObj,
                $orderItemsData
            );
        }

        \DB::commit();

    }

}
