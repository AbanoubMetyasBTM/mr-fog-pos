<?php

namespace App\Http\Controllers\admin;

use App\btm_form_helpers\CommonBetweenOrderAndGiftCard;
use App\btm_form_helpers\image;
use App\Events\Wallets\DepositMoneyForWallet;
use App\Events\Wallets\WithdrawMoneyFromWallet;
use App\Http\Controllers\AdminBaseController;
use App\Http\Controllers\traits\RegisterSessionLogTrait;
use App\models\branch\branches_m;
use App\models\client\clients_m;
use App\models\gift_card\gift_card_templates_m;
use App\models\gift_card\gift_cards_m;
use App\models\wallets_m;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GiftCardsController extends AdminBaseController
{

    use RegisterSessionLogTrait;

    public $modelClass;

    public function __construct()
    {
        parent::__construct();
        $this->setMetaTitle("Gift Cards");
    }

    public function index(Request $request)
    {
        havePermissionOrRedirect("admin/gift_cards", "show_action");

        $this->data["request_data"]  = (object)$request->all();
        $conds                       = $request->all();
        $conds['paginate']           = 50;
        $this->data["results"]       = gift_cards_m::getAllGiftCards($conds);
        $this->data["all_employees"] = User::getAllUsersWithTypeOrSpecificBranch('employee');
        $this->data["branches"]      = branches_m::getAllBranchesOrCurrentBranchOnly();
        $this->data["all_clients"]   = clients_m::getAllClientsOrSpecificBranch();

        return $this->returnView($request, "admin.subviews.gift_cards.show");
    }

    public function showCard(Request $request, $cardId)
    {
        $this->data["card"] = gift_cards_m::getAllGiftCards([
            "card_id"    => $cardId,
            "return_obj" => "yes",
        ]);

        return $this->returnView($request, "admin.subviews.gift_cards.show_card");
    }

    public function printCard(Request $request, $cardId)
    {

        $this->data["card"] = gift_cards_m::getAllGiftCards([
            "card_id"    => $cardId,
            "return_obj" => "yes",
        ]);

        $this->data["template_obj"] = gift_card_templates_m::findOrFail($this->data["card"]->card_template_id);


        return $this->returnView($request, "admin.subviews.gift_cards.print_card");
    }



    private function checkIfClientWalletIsUsableForGiftCard(Request $request)
    {
        $client       = clients_m::getClientDataById($request->get('client_id'));
        $clientWallet = wallets_m::getWalletById($client->wallet_id);

        if (floatval($clientWallet->wallet_amount) < 0){
            return json_encode([
                "error" => "You can not use wallet because you have minus balance"
            ]);
        }

        if (floatval($clientWallet->wallet_amount) < floatval($request->get('wallet_paid_amount'))){
            return json_encode([
                'error' => "The available amount in the wallet is  $clientWallet->wallet_amount"
            ]);
        }

    }

    public function addCardValidation(Request $request)
    {
        $rules_values = [];
        $rules_itself = [];

        $rules_values["card_template_id"]        = $request->get("card_template_id");
        $rules_values["branch_id"]               = $request->get("branch_id");
        $rules_values["client_id"]               = $request->get("client_id");
        $rules_values["card_title"]              = $request->get("card_title");
        $rules_values["card_price"]              = $request->get("card_price");
        $rules_values["cash_paid_amount"]        = $request->get("cash_paid_amount");
        $rules_values["debit_card_paid_amount"]  = $request->get("debit_card_paid_amount");
        $rules_values["credit_card_paid_amount"] = $request->get("credit_card_paid_amount");
        $rules_values["cheque_paid_amount"]      = $request->get("cheque_paid_amount");
        $rules_values["wallet_paid_amount"]      = $request->get("wallet_paid_amount");

        // check wallet value
        if($rules_values["wallet_paid_amount"] != ''){
            $result = json_decode($this->checkIfClientWalletIsUsableForGiftCard($request), true);

            if (isset($result['error'])){
                return [
                    "error" => $result['error'],
                ];
            }
        }

        $totalAmount = $this->calculateAmountOfGiftCard($request);


        if ($totalAmount != floatval($rules_values["card_price"])) {
            return [
                "error" => "The amount required to be paid is not equal to the price of the card",
            ];
        }

        $rules_itself["card_template_id"]        = "required|exists:gift_card_templates,template_id";
        $rules_itself["branch_id"]               = "required|exists:branches,branch_id";
        $rules_itself["client_id"]               = "required|exists:clients,client_id";
        $rules_itself["card_title"]              = "required|string";
        $rules_itself["card_price"]              = "required|numeric|min:1";


        $validator = \Validator::make($rules_values, $rules_itself);
        return $this->returnValidatorMsgs($validator);
    }


    public function addCard(Request $request)
    {

        havePermissionOrRedirect("admin/gift_cards", "add_action");

        if ($this->current_user_data->user_role == "admin") {
            return $this->returnMsgWithRedirection($request,"admin/dashboard","you can not add order, login as employee");
        }

        // check if is set branch id
        if (is_null($request->session()->get('current_branch_id'))){
            return $this->returnMsgWithRedirection(
                $request,
                'admin',
                'You must choose the branch first'
            );
        }

        // check if employee start register session
        if ($this->checkIsSetRegisterSessionIdInSession() === false){
            return $this->returnMsgWithRedirection(
                $request,
                'admin/registers',
                'You must start the session on your register first'
            );
        }


        $this->data["templates"] = gift_card_templates_m::getAllGiftCardsTemplates();
        $this->data["branches"]  = branches_m::getAllBranchesOrCurrentBranchOnly();
        $this->data["clients"]   = clients_m::getAllClientsOrSpecificBranch();

        if ($request->method() == "POST") {
            $validator = $this->addCardValidation($request);
            if ($validator !== true) {
                return $validator;
            }

            if ($this->branch_id != null) {
                $request["branch_id"] = $this->branch_id;
            }

            // upload receipt images
            $request = CommonBetweenOrderAndGiftCard::uploadReceiptsImages($request);

            \DB::beginTransaction();
             // create wallet for gift card
            $wallet = wallets_m::saveWallet(0);

            $request->request->add([
                'wallet_id'            => $wallet->wallet_id,
                'employee_id'          => $this->user_id,
                'register_id'          => $request->session()->get('register_id'),
                'register_session_id'  => $request->session()->get('register_session_id'),
                'card_expiration_date' => Carbon::now()->addYear(),
                'card_unique_number'   => $this->generateUniqueGiftCardCode(16)
            ]);


            $branchData      = branches_m::getBranchById($request->get("branch_id"));
            $gifCardTimezone = $branchData->branch_timezone;
            // create card
            $giftCardData = gift_cards_m::create(
                array_merge(
                    $request->all(),
                    ['gift_card_timezone' => $gifCardTimezone]
                )
            );

            $giftCardData           = gift_cards_m::getGiftCardById($giftCardData->card_id);
            $clientData             = clients_m::getClientDataById($request->get('client_id'));
            $branchData             = branches_m::getBranchById($request->get('branch_id'));
            $amount                 = $this->calculateAmountOfGiftCard($request);

            if ($giftCardData->wallet_paid_amount != null || floatval($giftCardData->wallet_paid_amount) > 0){

                $amountWillWithdrawFromWallet = floatval($giftCardData->wallet_paid_amount);
                $withdrawNotes                = "has been withdrawn money ($amountWillWithdrawFromWallet) from
                                                ($clientData->client_name) for gift card ($giftCardData->card_id)";

                event(new WithdrawMoneyFromWallet(
                    $this->user_id,
                    $clientData->wallet_id,
                    $clientData->client_name,
                    $branchData->branch_currency,
                    $amountWillWithdrawFromWallet,
                    $withdrawNotes,
                    false,
                    false
                ));
            }



            $depositMoneyToGiftCardNotes = "amount ($amount) has been deposited to gift card ($giftCardData->card_id)";

            event(new DepositMoneyForWallet(
                $this->user_id,
                $wallet->wallet_id,
                $clientData->client_name,
                $branchData->branch_currency,
                $amount,
                $depositMoneyToGiftCardNotes
            ));


            $this->addMoneyToBranchWalletsAfterAddCard($request, $giftCardData, $clientData);

            // create register session log
            $this->createRegisterSession(
                $request->session()->get('register_session_id'),
                $giftCardData->card_id,
                'gift_card',
                'increase',
                $giftCardData->cash_paid_amount,
                $giftCardData->debit_card_paid_amount,
                $giftCardData->credit_card_paid_amount,
                $giftCardData->cheque_paid_amount
            );

            \DB::commit();

            createLog($request, [
                'user_id'        => $this->user_id,
                'module'         => 'Gift-Cards',
                'module_content' => json_encode($request->all()),
                'action_url'     => url()->full(),
                'action_type'    => 'add-Card'
            ]);

            return $this->returnMsgWithRedirection(
                $request,
                'admin/gift-cards',
                'Gift card added successfully',
                true
            );
        }

        return $this->returnView($request, "admin.subviews.gift_cards.add_card");

    }

    private function addMoneyToBranchWalletsAfterAddCard(Request $request, $giftCardData, $clientData)
    {
        //add money to branch wallets
        $branchData = branches_m::getBranchById($request->get('branch_id'));

        $arrMap = [
            "cash_paid_amount"        => 'cash_wallet_id',
            "debit_card_paid_amount"  => 'debit_card_wallet_id',
            "credit_card_paid_amount" => 'credit_card_wallet_id',
            "cheque_paid_amount"      => 'cheque_wallet_id'
        ];

        foreach ($arrMap as $paidType => $walletType){

            if (!$request->filled($paidType)) {
               continue;
            }

            $walletObj                = wallets_m::getWalletById($branchData->{$walletType});
            $depositToCashWalletNotes = "(".$request->get($paidType) . ") added to the wallet due to
            the purchase of a gift card ($giftCardData->card_id)";

            event(new DepositMoneyForWallet(
                $this->user_id,
                $walletObj->wallet_id,
                $clientData->client_name,
                $branchData->branch_currency,
                $request->get($paidType),
                $depositToCashWalletNotes
            ));


        }

    }

    private function generateUniqueGiftCardCode($length)
    {
        do {
            $code =  mt_rand(pow(10,($length-1)),pow(10,$length)-1);
        }
        while (gift_cards_m::where("card_unique_number", "=", $code)->first());

        return $code;
    }




    private function calculateAmountOfGiftCard (Request $request)
    {
        return
            floatval($request->get("cash_paid_amount")) +
            floatval($request->get("debit_card_paid_amount")) +
            floatval($request->get("credit_card_paid_amount")) +
            floatval($request->get("cheque_paid_amount"))+
            floatval($request->get("wallet_paid_amount"));
    }
}
