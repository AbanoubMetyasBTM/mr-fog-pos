<div class="hide_until_select_client row" id="loyalty_points_row">
    <div class="col-md-12">
        <div class="section-wrapper mg-y-5">
            <label class="section-title">Loyalty Points</label>
            <p class="mg-b-20 mg-sm-b-20"></p>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Your Points</label>
                        <input type="text" id="available_points_in_wallet" class="form-control" readonly>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <?php if($all_points_redeems->count()): ?>
                            <label>Available Redeems</label> <br>
                            <label class="btn btn-info mr-1">
                                <input type="radio" class="selected_redeem_class" name="selected_redeem" data-reward_money="0" checked value="0">
                                None
                            </label>

                            <?php foreach ($all_points_redeems as $key=>$redeem): ?>
                                <label class="btn btn-info mr-1 available_redeems" data-points_value="{{$redeem->points_amount}}">
                                    <input type="radio" class="selected_redeem_class" name="selected_redeem" data-reward_money="{{$redeem->reward_money}}" value="{{$redeem->id}}">
                                    for {{$redeem->points_amount}} P/
                                    off {{$redeem->reward_money}} {{$redeem->money_currency}}
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <label for=""></label>
                            <div class="alert alert-info">
                                There's no redeems for your branch, please contact main admin to add redeems for your branch
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
