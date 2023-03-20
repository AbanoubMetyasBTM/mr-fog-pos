<?php if(havePermission("admin/money_installments","show_schedule_money")): ?>
    <a href="{{url("admin/money-installments/show-schedule-money/$walletName/$item->wallet_id")}}" class="lp_link">
        <i class="fa-brands fa-instalod"></i>
        <span>Money Installments</span>
    </a>
<?php endif; ?>



<?php if($item->wallet_amount > 0 ): ?>

    <?php if(havePermission("admin/money_installments","schedule_all_debt_money")): ?>
        <a href="{{url("admin/money-installments/get-schedule-all-debt-money/$walletName/$item->wallet_id")}}" class="lp_link">
            <i class="fa-brands fa-instalod"></i>
            <i class="fa-solid fa-plus abs_i"></i>
            <span>Schedule All Debt Money</span>
        </a>
    <?php endif; ?>


    <?php if(havePermission("admin/money_installments","add_schedule_debt_money")): ?>
        <a href="{{url("admin/money-installments/get-schedule-debt-money/$walletName/$item->wallet_id")}}" class="lp_link">
            <i class="fa-brands fa-instalod"></i>
            <i class="fa-solid fa-plus abs_i"></i>
            <span>Schedule Specific Debt Money</span>
        </a>
    <?php endif; ?>

<?php elseif ($item->wallet_amount < 0): ?>
    <?php if(havePermission("admin/money_installments","schedule_all_owed_money")): ?>
        <a href="{{url("admin/money-installments/get-schedule-all-owed-money/$walletName/$item->wallet_id")}}" class="lp_link">
            <i class="fa-brands fa-instalod"></i>
            <i class="fa-solid fa-plus abs_i"></i>
            <span>Schedule All Owed Money</span>
        </a>
    <?php endif; ?>

    <?php if(havePermission("admin/money_installments","add_schedule_owed_money")): ?>
        <a href="{{url("admin/money-installments/get-schedule-owed-money/$walletName/$item->wallet_id")}}" class="lp_link">
            <i class="fa-brands fa-instalod"></i>
            <i class="fa-solid fa-plus abs_i"></i>
            <span>Schedule Specific Owed Money</span>
        </a>
    <?php endif; ?>

<?php endif; ?>
