<?php

namespace App\Jobs\supplierOrders;

use App\btm_form_helpers\AddOrderHelper;
use App\btm_form_helpers\AddSupplierOrderHelper;
use App\btm_form_helpers\CommonBetweenOrderAndGiftCard;
use App\models\branch\branch_prices_m;
use App\models\branch\branches_m;
use App\models\client\clients_m;
use App\models\client\clients_order_items_m;
use App\models\client\clients_orders_m;
use App\models\coupon\coupons_m;
use App\models\coupon\used_coupons_m;
use App\models\product\product_promotions_m;
use App\models\supplier\supplier_order_items_m;
use App\models\supplier\suppliers_orders_m;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class MakeSupplierOrderDoneJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;


    public array $attrs;

    public function __construct(array $attrs = [])
    {
        /*
         $attrs = [
                'orderId'     => ,
                'requestData' => ,
        ];

         */
        $this->attrs = $attrs;
    }


    public function handle()
    {

        $requestData = $this->attrs['requestData'];

        $request = Request::capture();
        $request = $request->merge($requestData);

        $orderData  = suppliers_orders_m::getAllSuppliersOrders($this->attrs['orderId']);
        $orderItems = supplier_order_items_m::getOrderItemsByOrderId($this->attrs['orderId']);


        \DB::beginTransaction();

        suppliers_orders_m::updateOrderToMakeDone($this->attrs['orderId'], $this->attrs['requestData']);
        AddSupplierOrderHelper::afterAddDoneSupplierOrder($request, $orderData, $orderItems);

        \DB::commit();

    }

}
