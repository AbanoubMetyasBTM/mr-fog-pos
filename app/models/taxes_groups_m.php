<?php

namespace App\models;

use App\form_builder\TaxesGroupsBuilder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class taxes_groups_m extends \Eloquent
{

    use SoftDeletes;

    protected $table = "taxes_groups";

    protected $primaryKey = "group_id";

    protected $dates = ["deleted_at"];

    protected $fillable = [
        'group_name', 'group_taxes',
    ];

    private static function getData(array $attrs = [])
    {

        $results = self::select(\DB::raw("
            taxes_groups.*
        "));

        return ModelUtilities::general_attrs($results, $attrs);

    }

    public static function getAllTaxesGroups(): Collection
    {

        return self::getData();

    }


}
