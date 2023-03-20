<?php

namespace App\Transformers;

use Carbon\Carbon;

class UserTransformer extends Transformer
{

    public function transformLoginUser(array $item): array
    {
        $data = [];

        $data = $this->_transformBasicUserData($data, $item);

        $data = $this->_transformToken($data, $item);

        return $data;
    }

    public function transformUserProfileData(array $user_obj): array
    {

        $data = [];
        $data = $this->_transformBasicUserData($data, $user_obj);

        return $data;
    }

    public function transformUserProfileImage(array $data, array $item): array
    {

        $data['image'] = get_image_from_json_obj($item["logo_img_obj"]);

        return $data;

    }


    private function _transformBasicUserData(array $data, array $item): array
    {
        $data['id']          = $item['user_id'];
        $data['user_enc_id'] = $item['user_enc_id'];
        $data['type']        = $item['user_type'];

        $data = $this->transformUserProfileImage($data, $item);

        $data['name']              = $item['full_name'];
        $data['phone_code']        = $item['phone_code'];
        $data['phone']             = $item['phone'];

        return $data;
    }

    private function _transformToken(array $data, array $item): array
    {
        $data['token'] = isset($item['token']) ? $item['token'] : '';
        return $data;
    }

    public function transformPushNotificationStatus(object $item): array
    {
        $data              = [];
        $data['send_push'] = isset($item['send_push']) ? intval($item['send_push']) : 0;

        return $data;
    }

    public function transformUserPushNotifications($items)
    {
        $data    = [];
        $allData = [];

        foreach ($items as $key => $item) {

            $extraPayload = json_decode($item['extraPayload'], true);

            $data['id']           = intval($item['id']);
            $data['title']        = $item['title'];
            $data['body']         = $item['body'];
            $data['image']        = $extraPayload['image'] ? url($extraPayload['image']) : "";
            $data['extraPayload'] = $extraPayload;
            $data['is_seen']      = $item['is_seen'];
            $data['date']         = Carbon::createFromTimestamp(strtotime($item['created_at']))->toDateTimeString();

            $allData[] = $data;
            $data      = [];
        }

        return array_values($allData);

    }

    public function transformLogList(array $items)
    {

        $data = [];

        foreach($items as $key => $item)
        {

            $data[] = $this->_transformBasicData([], $item);

        }

        return $data;
    }

    private function _transformBasicData(array $itemData, object $item) :array
    {

        $itemData["id"]                     = intval($item->t_log_id);
        $itemData["title"]                  = $item->transaction_notes;
        $itemData["type"]                   = $item->transaction_type;
        $itemData["amount"]                 = returnPriceAndCurrencyInArray($item->transaction_amount);
        $itemData["operation"]              = $item->transaction_operation;
        $itemData["used_at"]                = $item->transaction_at;

        return $itemData;
    }

}
