<div class="hide_until_select_client row" id="payment_method_row">
    <div class="col-md-12">
        <div class="section-wrapper mg-y-5" id="payment_methods_section">
            <label class="section-title">Payment Methods</label>
            <p class="mg-b-20 mg-sm-b-20"></p>
            <table class="table">
                <tr>
                    <td class="wd-20p-force">
                        <label>Remain Amount</label>
                        <input id="remain_amount" class="form-control" readonly>
                    </td>
                    <td class="wd-20p-force">
                        <label>Cash Paid Amount</label>
                        <input type="number" class="form-control payment_method" name="cash_paid_amount" step="0.01">
                    </td>
                    <td class="wd-20p-force">
                        <label>Debit Card Paid Amount</label>
                        <input type="number" class="form-control payment_method" name="debit_card_paid_amount" step="0.01">
                    </td>
                    <td class="wd-20p-force">
                        <label>Credit Card Paid Amount</label>
                        <input type="number" class="form-control payment_method" name="credit_card_paid_amount" step="0.01">
                    </td>
                    <td class="wd-20p-force">
                        <label>Cheque Paid Amount</label>
                        <input type="number" class="form-control payment_method" name="cheque_paid_amount" step="0.01">
                    </td>
                </tr>

                <?php if(false):?>
                <tr>
                    <td></td>
                    <td>
                        <label>Debit Card Receipt Image</label>
                        <input id="formFile" type="file" class="form-control" name="debit_card_receipt_img">
                    </td>
                    <td>
                        <label>Credit Card Receipt Image</label>
                        <input type="file" class="form-control" name="credit_card_receipt_img">
                    </td>
                    <td>
                        <label>Cheque Receipt Image</label>
                        <input type="file" class="form-control" name="cheque_card_receipt_img">
                    </td>
                </tr>
                <?php endif;?>
            </table>
        </div>
    </div>
</div>
