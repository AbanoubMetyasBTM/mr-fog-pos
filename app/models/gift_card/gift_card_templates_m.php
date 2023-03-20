<?php

namespace App\models\gift_card;

use App\models\ModelUtilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class gift_card_templates_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "gift_card_templates";

    protected $primaryKey = "template_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
       'template_id', 'template_title', 'template_bg_img_obj',
       'template_text_positions', 'template_text_color',
       'is_active',
    ];



    public static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            gift_card_templates.*
        "));



        return ModelUtilities::general_attrs($results, $attrs);
    }

    public static function getAllGiftCardsTemplatesForAdminPanel($attrs=[]): Collection
    {
        return self::getData();
    }

    public static function getAllGiftCardsTemplates($attrs=[]): Collection
    {
        return self::getData([
            "free_conds" => [
                "gift_card_templates.is_active = 1"
            ],
        ]);
    }

}
