<?php

function pureLangUrl($segments){

    if($segments=="/"){
        $segments = "";
    }

    $segments = ltrim($segments, '/');
    $langSeg  = Session::get('lang_url_segment');
    $segments = $langSeg . $segments;
    $segments = ltrim($segments, '/');
    $segments = rtrim($segments, '/');

    return $segments;

}

function langUrl($segments){

    return url(pureLangUrl($segments));

}

function frontUrl(){

    return url("/public/front");

}

function langUrlForSiteContent($segments)
{

    $segments = str_replace(url("/"), "", $segments);

    if (strpos($segments, "http://") !== false) {
        return $segments;
    }

    if (strpos($segments, "https://") !== false) {
        return $segments;
    }


    return url(pureLangUrl($segments));

}


function getPaginationParams()
{
    $params = \Request::except(['page','load_inner', 'pages_is_loaded']);

    return http_build_query($params);

}
