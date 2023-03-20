<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\FrontController;
use Illuminate\Http\Request;

class HomeController extends FrontController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {


        $this->data["meta_general"]  = showContent("site_meta.homepage_general_meta");
        if($this->data["meta_general"] == "Homepage General Meta"){
            $this->data["meta_general"] = "";
        }

        $this->data["meta_title"]    = showContent("site_meta.homepage_meta_title");
        $this->data["meta_desc"]     = showContent("site_meta.homepage_meta_desc");
        $this->data["meta_keywords"] = showContent("site_meta.homepage_meta_keywords");

        return $this->returnView($request, "front.subviews.front_pages.index");

    }

    public function allServicesPage(Request $request){

        $this->data["meta_title"]    = showContent("site_meta.all_services_meta_title");
        $this->data["meta_desc"]     = showContent("site_meta.all_services_meta_desc");
        $this->data["meta_keywords"] = showContent("site_meta.all_services_meta_keywords");

        return $this->returnView($request, "front.subviews.front_pages.all_services");

    }

    public function graphicsServicePage(Request $request){

        $this->data["meta_title"]    = showContent("site_meta.graphics_service_meta_title");
        $this->data["meta_desc"]     = showContent("site_meta.graphics_service_meta_desc");
        $this->data["meta_keywords"] = showContent("site_meta.graphics_service_meta_keywords");

        return $this->returnView($request, "front.subviews.front_pages.service_graphics");

    }

    public function socialMediaServicePage(Request $request){

        $this->data["meta_title"]    = showContent("site_meta.social_media_service_meta_title");
        $this->data["meta_desc"]     = showContent("site_meta.social_media_service_meta_desc");
        $this->data["meta_keywords"] = showContent("site_meta.social_media_service_meta_keywords");

        return $this->returnView($request, "front.subviews.front_pages.social_media");

    }

    public function websiteServicePage(Request $request){

        $this->data["meta_title"]    = showContent("site_meta.website_service_meta_title");
        $this->data["meta_desc"]     = showContent("site_meta.website_service_meta_desc");
        $this->data["meta_keywords"] = showContent("site_meta.website_service_meta_keywords");

        return $this->returnView($request, "front.subviews.front_pages.service_web_sites");

    }

    public function seoServicePage(Request $request){

        $this->data["meta_title"]    = showContent("site_meta.seo_service_meta_title");
        $this->data["meta_desc"]     = showContent("site_meta.seo_service_meta_desc");
        $this->data["meta_keywords"] = showContent("site_meta.seo_service_meta_keywords");

        return $this->returnView($request, "front.subviews.front_pages.service_seo");

    }


}
