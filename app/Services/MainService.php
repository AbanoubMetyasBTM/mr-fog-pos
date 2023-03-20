<?php

namespace App\Services;

use App\Helpers\MessageHandleHelper;


class MainService {

    protected $messageHandler;

    public function __construct() {

        $this->messageHandler       = new MessageHandleHelper();
    }

}
