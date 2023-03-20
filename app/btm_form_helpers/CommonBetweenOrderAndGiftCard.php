<?php


namespace App\btm_form_helpers;


use Illuminate\Http\Request;

class CommonBetweenOrderAndGiftCard
{


    public static function uploadReceiptsImages(Request $request): Request
    {

        $files = [
            "debit_card_receipt_img"  => "debit_card_paid_amount",
            "credit_card_receipt_img" => "credit_card_paid_amount",
            "cheque_card_receipt_img" => "cheque_paid_amount",
        ];

        foreach ($files as $fileName=>$amountFieldName){
            if (!$request->filled($amountFieldName) || !$request->has($fileName)) {
                continue;
            }

            $imgObj = image::general_save_img_without_attachment($request, [
                "img_file_name" => $fileName,
            ]);

            $request[$fileName."_obj"] = json_encode($imgObj);
        }

        return $request;

    }



}
