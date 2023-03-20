<?php

namespace App\Http\Middleware\API;

use App\Exceptions\notPermittedApi;
use App\Http\Middleware\middlewareTrait;
use Closure;

class APILocalization {

    use middlewareTrait;

    public function handle($request, Closure $next) {

        #region localization

            $language =  $request->header('Accept-Language'); // ex. en or ar
            $language = clean($language);

            $reqLangId = $this->getLangId($language);
            if($reqLangId == 0)
            {
                throw new notPermittedApi();
            }

            config()->set('default_language.primary_lang_id', $reqLangId);
            config()->set('default_language.primary_lang_title', $language);

        #endregion

        $this->setupConfig();

        return $next($request);
    }


}
