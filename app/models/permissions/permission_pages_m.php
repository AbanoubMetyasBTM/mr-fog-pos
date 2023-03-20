<?php

namespace App\models\permissions;

class permission_pages_m
{
    public static $attrs = [
        'page_name', 'sub_sys',
        'show_in_admin_panel', 'hide_accept_buttons',
        'all_additional_permissions'
    ];

    public static function all()
    {

        if (!file_exists("permissions")) {
            return collect([]);
        }


        $return_objs = [];

        //get all files from folder generate_site_content_methods
        $files = scandir("permissions", 1);

        foreach ($files as $file_id) {
            if (!is_file("permissions/{$file_id}")) continue;

            $files_contents = file_get_contents("permissions/{$file_id}");

            if ($files_contents === false) continue;

            $files_contents = json_decode($files_contents);

            $return_objs[] = $files_contents;
        }


        return collect($return_objs);
    }

    public static function find($page_name)
    {
        if (!file_exists("permissions/".string_safe($page_name).".json")) {
            return null;
        }

        $files_contents = file_get_contents("permissions/".string_safe($page_name).".json");

        if ($files_contents === false) return null;

        $files_contents = json_decode($files_contents);

        return $files_contents;
    }


    public static function findOrFail($page_name)
    {
        if (!file_exists("permissions/".string_safe($page_name).".json")) {
            return abort(404);
        }

        $files_contents = file_get_contents("permissions/".string_safe($page_name).".json");

        if (!$files_contents) return abort(404);

        $files_contents = json_decode($files_contents);

        return $files_contents;
    }

    public static function getWhereSubSys($sub_sys)
    {

        if (empty($sub_sys)){
            $sub_sys = "admin";
        }

        $filteredData = [];

        $all_data = self::all();
        foreach ($all_data as $item){
            if (strpos($item->sub_sys,$sub_sys) !== false){
                $filteredData[] = $item;
            }
        }

        return collect($filteredData);
    }

    public static function create_folders()
    {
        if (!file_exists("permissions")) {
            mkdir("permissions");
            chmod("permissions",0775);
        }
    }

    public static function save_only_fillable_data($full_arr)
    {
        $return_arr = [];

        foreach (self::$attrs as $attr) {
            $return_arr[$attr] = "";
            if (isset($full_arr[$attr])) {
                $return_arr[$attr] = $full_arr[$attr];
            }
        }

        return $return_arr;
    }

    public static function create($post_arr)
    {

        self::create_folders();

        $filename = "permissions/" . string_safe($post_arr["page_name"]) . ".json";

        $file = fopen($filename, "w");
        chmod($filename,0775);

        file_put_contents(
            $filename,
            json_encode(self::save_only_fillable_data($post_arr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        fclose($file);

        return (object)$post_arr;
    }

    public static function update($post_arr)
    {

        $filename = "permissions/" . string_safe($post_arr["page_name"]) . ".json";

        file_put_contents(
            $filename,
            json_encode(self::save_only_fillable_data($post_arr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return (object)$post_arr;
    }

    public static function destroy($page_name)
    {
        $files_contents = file_get_contents("permissions/" . string_safe($page_name) . ".json");

        if (!$files_contents) return abort(404);

        unlink("permissions/" . string_safe($page_name) . ".json");
    }
}
