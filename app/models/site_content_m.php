<?php

namespace App\models;

class site_content_m
{

    public static $attrs = ["content_title", "content_json", "lang_title"];

    public static function findOrFail($lang_title, $content_title)
    {
        if (!file_exists(base_path() . "/site_content/data/lang_{$lang_title}/{$content_title}.json")) {
            return abort(404);
        }

        $files_contents = file_get_contents(base_path() . "/site_content/data/lang_{$lang_title}/{$content_title}.json");

        if (!$files_contents) return abort(404);

        $files_contents = json_decode($files_contents);

        return $files_contents;
    }

    public static function getWhere($lang_title, $content_title)
    {

        if (!is_file(base_path() . "/site_content/data/lang_{$lang_title}/{$content_title}.json")) return "not found";

        $files_contents = file_get_contents(base_path() . "/site_content/data/lang_{$lang_title}/{$content_title}.json");

        if (!$files_contents) return "not found";

        $files_contents = json_decode($files_contents);

        return $files_contents;
    }

    public static function create_site_content_folders($lang_title)
    {

        if (!file_exists(base_path() . "/site_content/data")) {
            mkdir(base_path() . "/site_content/data", 0775);
            chmod(base_path() . "/site_content/data/", 0775);
        }

        if (!file_exists(base_path() . "/site_content/data/lang_{$lang_title}")) {
            mkdir(base_path() . "/site_content/data/lang_{$lang_title}", 0775);
            chmod(base_path() . "/site_content/data/lang_{$lang_title}/", 0775);
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

    public static function create($lang_title, $content_title, $post_arr)
    {

        self::create_site_content_folders($lang_title);

        $filename = base_path() . "/site_content/data/lang_{$lang_title}/{$content_title}.json";

        $file = fopen($filename, "w");
//        chmod($filename, 0775);

        file_put_contents(
            $filename,
            json_encode(self::save_only_fillable_data($post_arr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
        fclose($file);

        if (env("enable_site_content_logs")){
            $logName = storage_path(
                "site_content_logs" . DIRECTORY_SEPARATOR .
                date("Y-m-d H:i:s")."-lang_{$lang_title}-{$content_title}-create-" . "-" . rand(1111, 9999) . ".json"
            );

            \File::put(
                $logName,
                json_encode(self::save_only_fillable_data($post_arr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }


        return (object)$post_arr;
    }

    public static function update($lang_title, $content_title, $post_arr)
    {

        $filename = base_path() . "/site_content/data/lang_{$lang_title}/{$content_title}.json";

        $f = fopen($filename, 'w');
        flock($f, LOCK_EX);

        fwrite(
            $f,
            json_encode(self::save_only_fillable_data($post_arr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );


        flock($f, LOCK_UN);
        fclose($f);


        if (env("enable_site_content_logs")) {
            $logName = storage_path(
                "site_content_logs" . DIRECTORY_SEPARATOR .
                date("Y-m-d H:i:s") . "-lang_{$lang_title}-{$content_title}-update-" . "-" . rand(1111, 9999) . ".json"
            );

            \File::put(
                $logName,
                json_encode(self::save_only_fillable_data($post_arr), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }



        return (object)$post_arr;
    }


}
