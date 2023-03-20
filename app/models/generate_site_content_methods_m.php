<?php

namespace App\models;

class generate_site_content_methods_m
{

    public static $attrs = [
        'id', 'method_name', 'method_requirments', 'method_img'
    ];


    public static function all()
    {

        if (!file_exists(base_path()."/site_content/methods")) {
            return collect([]);
        }


        $return_objs = [];

        //get all files from folder generate_site_content_methods
        $files = scandir(base_path()."/site_content/methods", 1);

        foreach ($files as $title) {
            if (!is_file(base_path()."/site_content/methods/{$title}")) continue;

            $files_contents = file_get_contents(base_path()."/site_content/methods/{$title}");

            if (!$files_contents) break;

            $files_contents = json_decode($files_contents);

            $return_objs[] = $files_contents;
        }


        return collect($return_objs);
    }

    public static function find($title)
    {
        if (!file_exists(base_path()."/site_content/methods/{$title}.json")) {
            return null;
        }

        $files_contents = file_get_contents(base_path()."/site_content/methods/{$title}.json");

        $files_contents = json_decode($files_contents);

        if (!is_object($files_contents)) return null;

        return $files_contents;
    }

    public static function findOrFail($title)
    {
        if (!file_exists(base_path()."/site_content/methods/{$title}.json")) {
            return abort(404);
        }

        $files_contents = file_get_contents(base_path()."/site_content/methods/{$title}.json");

        if (!$files_contents) return abort(404);

        $files_contents = json_decode($files_contents);

        return $files_contents;
    }

    public static function getWhere($title)
    {

        return self::find($title);

    }

    public static function create_site_content_folders()
    {

        if (!file_exists(base_path()."/site_content")) {
            mkdir(base_path()."/site_content");
        }

        if (!file_exists(base_path()."/site_content/methods")) {
            mkdir(base_path()."/site_content/methods");
        }

        if (!file_exists(base_path()."/site_content/methods/imgs")) {
            mkdir(base_path()."/site_content/methods/imgs");
        }

        if (!file_exists(base_path()."/site_content/data")) {
            mkdir(base_path()."/site_content/data");
        }

    }

    public static function save_only_fillable_data($full_arr)
    {
        $return_arr = [];

        foreach (self::$attrs as $attr) {
            if (isset($full_arr[$attr])) {
                $return_arr[$attr] = $full_arr[$attr];
            }
        }

        return $return_arr;
    }

    public static function create($data_arr)
    {

        self::create_site_content_folders();

        $filename = base_path()."/site_content/methods/{$data_arr["method_name"]}.json";

        $file = fopen($filename, "w");
        chmod($filename, 0775);

        file_put_contents(
            $filename,
            json_encode(self::save_only_fillable_data($data_arr), JSON_PRETTY_PRINT)
        );
        fclose($file);

        return (object)$data_arr;
    }

    public static function update($title, $data_arr)
    {

        file_put_contents(
            base_path()."/site_content/methods/{$title}.json",
            json_encode(self::save_only_fillable_data($data_arr), JSON_PRETTY_PRINT)
        );

        return (object)$data_arr;
    }

    public static function destroy($title)
    {
        $files_contents = file_get_contents(base_path()."/site_content/methods/{$title}.json");

        if (!$files_contents) return abort(404);

        unlink(base_path()."/site_content/methods/{$title}.json");
    }


}
