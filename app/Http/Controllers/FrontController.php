<?php

namespace App\Http\Controllers;

use App\btm_form_helpers\image;
use App\btm_form_helpers\site_content;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class FrontController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $data = [];
    public $primary_lang_id;
    public $primary_lang_title;

    public function __construct()
    {

        parent::__construct();


        $this->loadDefaultLang();
        $this->selectLanguage();
        $this->langSegment();
        $this->showSiteContentBadges();

    }


    public function showSiteContentBadges()
    {
        if (isset($_GET["show_admin_content"]) && in_array($_GET["show_admin_content"], ["yes", "no"])) {
            session()->put('show_admin_content', $_GET["show_admin_content"]);
            session()->save();
        }
    }

    public function loadDefaultLang()
    {

        $this->data["all_langs"] = $this->getAllLangsForFront();
        $defaultLang             = $this->data["all_langs"]->where("lang_is_default", 1)->first();

        if (!is_object($defaultLang)) {
            $defaultLang = $this->data["all_langs"]->first();
        }

        config()->set("default_language.main_lang_id", $defaultLang->lang_id);
        config()->set("default_language.main_lang_title", $defaultLang->lang_title);
        config()->set("default_language.primary_lang_id", $defaultLang->lang_id);
        config()->set("default_language.primary_lang_title", $defaultLang->lang_title);


        $this->primary_lang_id    = config("default_language.primary_lang_id");
        $this->primary_lang_title = config("default_language.primary_lang_title");

    }

    public function langSegment()
    {

        $segments                = \Request::segments();
        $query_string            = "?" . \Request::getQueryString();
        $this->data["all_langs"] = $this->getAllLangsForFront();

        if (isset($segments[0]) && $segments[0] == "social-callback") {
            return;
        }

        //change lang

        if (isset($segments[0]) && strlen($segments[0]) == 5) {
            $all_lang_titles = $this->data["all_langs"]->groupBy("lang_title")->all();

            if (isset($all_lang_titles[$segments[0]])) {
                Session::put("lang_id", $all_lang_titles[$segments[0]]->first()->lang_id);
                Session::put("lang_title", $all_lang_titles[$segments[0]]->first()->lang_title);
            }
        }

        $session_lang_id = Session::get("lang_id");

        if (!empty($session_lang_id)) {
            $this->primary_lang_id    = $session_lang_id;
            $this->primary_lang_title = Session::get("lang_title");
        }


        if ($this->primary_lang_id != config("default_language.main_lang_id")) {

            if (!isset($segments[0])) {
                Session::put("lang_id", config("default_language.main_lang_id"));
                Session::put("lang_title", config("default_language.main_lang_title"));

                $this->primary_lang_id    = config("default_language.main_lang_id");
                $this->primary_lang_title = config("default_language.main_lang_title");

            }

            if (isset($segments[0]) && strlen($segments[0]) > 5) {
                Session::put("lang_id", config("default_language.main_lang_id"));
                Session::put("lang_title", config("default_language.main_lang_title"));

                $this->primary_lang_id    = config("default_language.main_lang_id");
                $this->primary_lang_title = config("default_language.main_lang_title");
            }

        }

        $current_url = implode("/", $segments);

        if (isset($segments[0]) && $segments[0] == config("default_language.main_lang_title")) {
            $segments_without_first_seg = $segments;
            unset($segments_without_first_seg[0]);
            $current_url = implode("/", $segments_without_first_seg);

            redirect($current_url)->send();
            die();
        }

        if ($this->primary_lang_id != config("default_language.main_lang_id")) {
            $segments_without_first_seg = $segments;
            unset($segments_without_first_seg[0]);
            $current_url = implode("/", $segments_without_first_seg);
        }

        $this->data["current_url"] = $current_url . $query_string;

        $this->data["lang_url_segment"] = "/";
        if ($this->primary_lang_id != config("default_language.main_lang_id")) {
            $this->data["lang_url_segment"] = $this->primary_lang_title . "/";
        }

        Session::put('lang_url_segment', $this->data["lang_url_segment"]);

        \Config::set('default_language.primary_lang_id', $this->primary_lang_id);
        \Config::set('default_language.primary_lang_title', $this->primary_lang_title);

        $this->data["main_lang_title"]     = config('default_language.main_lang_title');
        $this->data["selected_lang_title"] = $this->primary_lang_title;
        $this->data["selected_lang_obj"]   = $this->data["all_langs"]->where("lang_title", $this->primary_lang_title)->first();


    }

    private function getQueryStringExcept(Request $request, $except)
    {

        $attrs = $request->all();

        if (isset($attrs[$except])) {
            unset($attrs[$except]);
        }

        return "?" . http_build_query($attrs);

    }

    public function selectLanguage()
    {

        $all_lang_titles = $this->data["all_langs"]->groupBy("lang_title")->all();
        $request         = Request::capture();

        $lang_title = $request->get("change_lang");
        if (empty($lang_title)) return;
        if (!isset($all_lang_titles[$lang_title])) return;

        $segments = $request->segments();

        //remove ar or fr from segment 0
        if (isset($segments[0]) && strlen($segments[0]) == 5) {

            if (isset($all_lang_titles[$segments[0]])) {
                unset($segments[0]);
            }
        }

        $current_url = implode("/", $segments);

        if ($lang_title == config("default_language.main_lang_title")) {
            redirect($current_url . $this->getQueryStringExcept($request, 'change_lang'))->send();
            die();
        }

        redirect($lang_title . "/" . $current_url . $this->getQueryStringExcept($request, 'change_lang'))->send();
        die();

    }


    //remove unwanted route parameters then there is no mess will happen
    public function callAction($method, $parameters)
    {
        if (isset($parameters["lang_title"])) {
            unset($parameters["lang_title"]);
        }

        return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function getFormattedImage($folder, $target_image)
    {
        return image::getFormattedImage($folder, $target_image);
    }


}
