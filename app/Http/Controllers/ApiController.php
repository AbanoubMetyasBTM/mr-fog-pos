<?php

namespace App\Http\Controllers;

use App\Helpers\MessageHandleHelper;
use App\Helpers\ValidationHelper;

class ApiController extends Controller
{

    protected $validator;
    protected $messageHandler;

    public function __construct()
    {
        $this->messageHandler       = new MessageHandleHelper();
        $this->validator            = new ValidationHelper();
    }

    protected function analysisApiResponse($results)
    {

        if (is_object($results))
        {
            $results = json_decode($results->content(), true);
        }

        if ($results["Code"] == 200)
        {
            $response       = [];
            if ($results["Data"] != null)
            {
                $response   = $results["Data"];
            }
            return $this->messageHandler->getJsonSuccessResponse($results["Message"], $response);
        }

        elseif ($results["Code"] == 422)
        {
            return $this->messageHandler->getJsonValidationErrorResponse($results["Message"], []);
        }

        elseif ($results["Code"] == 402)
        {
            return $this->messageHandler->getJsonSessionExpiredErrorResponse($results["Message"], []);
        }

        elseif (in_array($results["Code"], [500, 405]))
        {
            // TODO bk: message will be updated later
            return $this->messageHandler->getJsonInternalServerErrorResponse($results["Message"], []);
        }

        else
        {
            return $this->messageHandler->getJsonBadRequestErrorResponse($results["Message"], []);
        }
    }

}
