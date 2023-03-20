<?php if(havePermission("admin/transactions_log","show_log")): ?>
    <a href="{{url("admin/transactions-log/show-log/$walletName/$item->wallet_id")}}" class="lp_link">
        <i class="fa-regular fa-file-lines"></i>
        <span>Transaction Log</span>
    </a>
<?php endif; ?>

<?php if(havePermission("admin/transactions_log","deposit_money")): ?>
    <a href="{{url("admin/transactions-log/deposit-money/$walletName/$item->wallet_id")}}" class="lp_link">
        <i class="fa-solid fa-plus"></i>
        <span>Deposit Money</span>
    </a>
<?php endif; ?>

<?php if(havePermission("admin/transactions_log","withdraw_money")): ?>
    <a href="{{url("admin/transactions-log/withdraw-money/$walletName/$item->wallet_id")}}" class="lp_link">
        <i class="fa-solid fa-minus"></i>
        <span>Withdraw Money</span>
    </a>
<?php endif; ?>
