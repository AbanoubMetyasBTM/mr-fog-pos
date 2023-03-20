<?php

namespace App\Providers;

use App\Events\InventoryProducts\AddReturnOrderFromInventory;
use App\Events\InventoryProducts\BuyProductFromInventory;
use App\Events\MoneyInstallments\ReceiveMoneyInstallment;
use App\Events\InventoryProducts\AddBrokenProductToInventory;
use App\Events\InventoryLogs\AddInvalidEntryProduct;
use App\Events\InventoryProducts\AddProductToInventory;
use App\Events\InventoryProducts\MoveProductToInventory;
use App\Events\Wallets\DepositMoneyForWallet;
use App\Events\Wallets\WithdrawMoneyFromWallet;
use App\Listeners\InventoryProducts\RunAfterAddBuyProductFromInventory;
use App\Listeners\InventoryProducts\RunAfterAddReturnOrderFromInventory;
use App\Listeners\MoneyInstallments\RunAfterReceiveMoneyInstallment;
use App\Listeners\InventoryProducts\RunAfterAddBrokenProductToInventory;
use App\Listeners\InventoryLogs\RunAfterAddInvalidEntryProduct;
use App\Listeners\InventoryProducts\RunAfterAddProductToInventory;
use App\Listeners\InventoryProducts\RunAfterMoveProductToAnotherInventory;
use App\Listeners\Wallets\RunAfterDepositMoneyForAgency;
use App\Listeners\Wallets\RunAfterWithdrawMoneyFromAgency;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

        DepositMoneyForWallet::class   => [
            RunAfterDepositMoneyForAgency::class
        ],
        WithdrawMoneyFromWallet::class     => [
            RunAfterWithdrawMoneyFromAgency::class
        ],
        AddProductToInventory::class       => [
            RunAfterAddProductToInventory::class
        ],
        MoveProductToInventory::class      => [
            RunAfterMoveProductToAnotherInventory::class
        ],
        AddBrokenProductToInventory::class => [
            RunAfterAddBrokenProductToInventory::class
        ],
        AddInvalidEntryProduct::class      => [
            RunAfterAddInvalidEntryProduct::class
        ],
        ReceiveMoneyInstallment::class     => [
            RunAfterReceiveMoneyInstallment::class
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
