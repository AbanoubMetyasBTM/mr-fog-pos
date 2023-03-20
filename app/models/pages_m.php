<?php

namespace App\models;

use App\form_builder\PagesBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class pages_m extends Model
{
    use SoftDeletes;

    protected $table = "pages";

    protected $primaryKey = "page_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'lang_id',
        'article_cat_id',
        'page_title', 'page_slug',
        'page_short_desc',
        'page_body_1',
        'page_body_2',
        'page_type', 'hide_page', 'page_order', 'page_is_featured', 'page_img_obj', 'page_visited_count',
        'page_general_meta',
        'page_meta_title', 'page_meta_desc', 'page_meta_keywords',
        'show_page_on_footer_menu', 'show_page_on_header_menu',
        'page_slider','page_services'
    ];

    static function getData($attrs)
    {

        $results = self::select(\DB::raw("
            pages.*
        "));

        $results = ModelUtilities::getTranslateData($results, "pages", new PagesBuilder());

        return ModelUtilities::general_attrs($results, $attrs);

    }

    static function getPagesCount($lang_id)
    {

        return self::where("lang_id", $lang_id)->where("page_type", "default")->count();

    }

    public static function getHeaderMenuPages($langId)
    {
        $cond   = [];
        $cond[] = ["pages.lang_id", "=", $langId];
        $cond[] = ["pages.page_type", "=", "default"];
        $cond[] = ["pages.show_page_on_header_menu", "=", "1"];
        $cond[] = ["pages.hide_page", "=", "0"];

        return self::getData([
            "cond"     => $cond,
            "order_by" => ["page_order", "asc"],
        ]);
    }

    public static function getFooterMenuPages($langId)
    {
        $cond   = [];
        $cond[] = ["pages.lang_id", "=", $langId];
        $cond[] = ["pages.page_type", "=", "default"];
        $cond[] = ["pages.show_page_on_footer_menu", "=", "1"];
        $cond[] = ["pages.hide_page", "=", "0"];

        return self::getData([
            "cond"     => $cond,
            "order_by" => ["page_order", "asc"],
        ]);
    }

    public static function getPageForFront($pageSLug, $langId){

        $cond   = [];
        $cond[] = ["pages.lang_id", "=", $langId];
        $cond[] = ["pages.page_type", "=", "default"];
        $cond[] = ["pages.hide_page", "=", 0];
        $cond[] = ["pages.page_slug", "=", Vsi($pageSLug)];

        return pages_m::getData([
            "cond"       => $cond,
            "return_obj" => "yes",
        ]);

    }



}
