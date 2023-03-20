<?php

namespace App\models;

use App\form_builder\CategoriesBuilder;
use App\form_builder\PagesBuilder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class categories_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "categories";

    protected $primaryKey = "cat_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'parent_id', 'cat_name', 'cat_img_obj'
    ];

    private static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            categories.*
        "));

        $results = ModelUtilities::getTranslateData($results, "categories", new CategoriesBuilder());

        if(isset($attrs["need_parent_join"])){
            $results = $results->leftJoin("categories as parent_cat","categories.parent_id","=","parent_cat.cat_id");
            $results = $results->addSelect(\DB::Raw("
                parent_cat.cat_id as 'parent_cat_id',
                ".JsF("parent_cat.cat_name","parent_cat_name")."
            "));
        }

        if(isset($attrs["need_combined_parent_sub_name"])){
            $results = $results->addSelect(\DB::Raw("
                concat(
                    ".JsF("parent_cat.cat_name","",true).",
                    ' - ',
                    ".JsF("categories.cat_name","",true)."
                ) as 'combined_name'
            "));
        }

        return ModelUtilities::general_attrs($results, $attrs);

    }

    public static function checkItHasAtLeastOneRow(): bool
    {
        $res = self::limit(1)->first();

        if(is_object($res)){
            return true;
        }
        return false;
    }

    public static function getParentCats(): Collection
    {

        return self::getData([
            "free_conds" => [
                "categories.parent_id = 0"
            ],
        ]);

    }

    public static function getAllCatsWithParents($attrs = []): Collection
    {
        $attrs["need_parent_join"] = true;

        return self::getData($attrs);

    }

    public static function getSubCats()
    {

        return self::getAllCatsWithParents([
            "need_combined_parent_sub_name" => true,
            "free_conds" => [
                "categories.parent_id > 0"
            ],
        ]);

    }


    public static function getSubCatsByParentId($parentId)
    {
        return self::getData([
            "free_conds" => [
                "categories.parent_id = $parentId"
            ],
        ]);
    }
}
