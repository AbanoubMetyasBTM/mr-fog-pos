<?php

namespace App\Exceptions;

use App\btm_form_helpers\ErrorHelper;
use App\Helpers\MessageHandleHelper;
use App\helpers\utility;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{

    protected $messageHandler;

    public function __construct(Container $container, MessageHandleHelper $messageHandler)
    {
        parent::__construct($container);

        $this->messageHandler = $messageHandler;
    }

    protected $dontReport = [
        \League\OAuth2\Server\Exception\OAuthServerException::class,
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];


    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    public function checkLinkIsNotAtAdminOrAgency($request)
    {

        $url = $request->url();

        if (strpos($url, "admin/") !== false) {
            return false;
        }

        if (strpos($url, "agent/") !== false) {
            return false;
        }

        return true;

    }

    public function render($request, Exception $exception)
    {

        if ($request->method() == "POST" && $this->checkLinkIsNotAtAdminOrAgency($request) && ($exception instanceof MethodNotAllowedHttpException)) {
            return redirect($request->url() . "?" . $request->getQueryString())->send();
        }


        if ($exception instanceof notPermittedApi) {
            $msg = "Missing or invalid headers on request";
            return $this->messageHandler->getJsonBadRequestErrorResponse($msg);
        }

        if ($request->wantsJson()) {
            return $this->_handleApiException($request, $exception);
        }

        if ($this->isHttpException($exception) && $exception->getStatusCode() == 404) {
            return redirect()->route('not_found_page');
        }

        if ($this->checkLinkIsNotAtAdminOrAgency($request) && ($exception instanceof TokenMismatchException)) {
            return redirect($request->url() . "?" . $request->getQueryString())->send();
        }

        if ($exception->getCode() == 0 && !($exception instanceof NotFoundHttpException)) {

            // for internal server error
            ErrorHelper::handleException($exception, $request);

        }

        return parent::render($request, $exception);
    }

    private function _handleApiException($request, Exception $exception)
    {

        $exception = $this->prepareException($exception);

        if ($exception instanceof NotFoundHttpException) {
            $msg = Response::$statusTexts[Response::HTTP_NOT_FOUND];

            return $this->messageHandler->getJsonNotFoundErrorResponse($msg);
        }
        elseif ($exception instanceof AuthenticationException) {
            $msg = Response::$statusTexts[Response::HTTP_UNAUTHORIZED];

            return $this->messageHandler->getJsonNotAuthorizedResponse($msg);
        }
        else {
            $msg = ErrorHelper::handleException($exception, $request);

            return $this->messageHandler->getJsonInternalServerErrorResponse($msg);
        }

    }


}
