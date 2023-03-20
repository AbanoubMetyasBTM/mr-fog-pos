<?php

namespace App\Http\Controllers\traits;

use App\Jobs\sendEmail;
use App\models\notification_m;
use Carbon\Carbon;

trait notificationsTrait
{

    // email to send any critical changes
    public $systemEmails = [
        "abanoub.metyas.btm@gmail.com"
    ];

    public function sendNotificationToUsers($user_ids, $not_title, $not_type, $field_id = 0){

        if(!isset_and_array($user_ids))return;

        $notifications_rows = [];
        $now                = Carbon::now();

        foreach ($user_ids as $user_id) {
            $notifications_rows[] = [
                'not_title'  => $not_title,
                'not_type'   => $not_type,
                'to_user_id' => $user_id,
                'target_id'  => $field_id,
                'created_at' => $now
            ];
        }

        notification_m::insert($notifications_rows);
    }

    public function sendNotificationToEmailsThatMainSettingsChanged($editedUser, $oldData, $newData)
    {

        $emailBody = \View::make("email.system.settings_data_changed")->with([
            "userData" => $editedUser,
            "oldData"  => $oldData,
            "newData"  => $newData,
        ])->render();

        foreach ($this->systemEmails as $key => $email) {
            dispatch(
                (new sendEmail([
                    "email"   => $email,
                    "subject" => "system main settings has been changed",
                    "data"    => $emailBody
                ]))->onQueue("not_important_queue")
            );
        }

    }

    public function sendNotificationToEmailsThatAgentMarkupSettingsChanged($editedUser, $agentName, $oldData, $newData)
    {

        $emailBody = \View::make("email.system.agent_markup_data_changed")->with([
            "userData"  => $editedUser,
            "agentName" => $agentName,
            "oldData"   => $oldData,
            "newData"   => $newData,
        ])->render();

        foreach ($this->systemEmails as $key => $email) {
            dispatch(
                (new sendEmail([
                    "email"   => $email,
                    "subject" => "agent '$agentName' markup settings has been changed",
                    "data"    => $emailBody
                ]))->onQueue("not_important_queue")
            );
        }

    }

    public function sendNotificationToEmailsThatAgentWalletAffected($editedUser, $agentName, $oldWallet, $newWallet, $notes)
    {

        $emailBody = \View::make("email.system.agent_wallet_changed")->with([
            "userData"  => $editedUser,
            "agentName" => $agentName,
            "oldWallet" => $oldWallet,
            "newWallet" => $newWallet,
            "notes"     => $notes,
        ])->render();

        foreach ($this->systemEmails as $key => $email) {
            dispatch(
                (new sendEmail([
                    "email"   => $email,
                    "subject" => "agent '$agentName' wallet has been changed",
                    "data"    => $emailBody
                ]))->onQueue("not_important_queue")
            );
        }

    }

}
