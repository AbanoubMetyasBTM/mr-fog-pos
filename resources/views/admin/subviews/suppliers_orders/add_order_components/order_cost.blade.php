<div class="row hidden_if_order_status_pending">
    <div class="col-md-12">
        <div class="section-wrapper mg-y-20">
            <label class="section-title">Order Cost</label>
            <p class="mg-b-20 mg-sm-b-40"></p>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Additional Fees</label>
                        <input id="additional_fees" type="number" name="additional_fees" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Additional Fees Description</label>
                        <textarea name="additional_fees_desc" class="form-control"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Items Cost + Additional Fees</label>

                        <input type="text" name="total_cost" value="{{isset($order)?$order->total_items_cost:''}}" class="form-control total_cost" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Items Order Cost</label>

                        <input type="text" name="total_items_cost" value="{{isset($order)?$order->total_items_cost:''}}" class="form-control total_items_cost" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Paid Amount</label>
                        <input type="number" id="paid_amount" name="paid_amount" class="form-control" max="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
