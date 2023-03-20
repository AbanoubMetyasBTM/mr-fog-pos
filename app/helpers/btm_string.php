<?php

function split_first_word_from_sentence($sentence, $other_words_tag)
{

    $string = "";
    $sentence = explode(" ",$sentence);
    $string .= $sentence[0]." ";
    $string .= "<$other_words_tag>";
    foreach ($sentence as $key => $value)
    {
        if($key > 0)
        {
            $string .= $value;
        }
    }
    $string .= "</$other_words_tag>";

    return $string;
}

function split_first_word_from_sentence_into_tag($sentence, $first_word_tag)
{

    $string = "";
    $sentence = explode(" ",$sentence);
    $string .= "<$first_word_tag>".$sentence[0]." "."</$first_word_tag>";
    foreach ($sentence as $key => $value)
    {
        if($key > 0)
        {
            $string .= $value;
        }
    }

    return $string;
}

function string_safe($string) {
    $except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|',' ','+','&','#',';','[',']');
    return str_replace($except, '-', $string);
}

function string_safe_underscore($string) {
    $except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|',' ','+','&','#',';','[',']','-');
    return str_replace($except, '_', $string);
}

function number_safe($string) {
    $except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|',' ','+','&','#',';','[',']','-');
    return str_replace($except, '', $string);
}


function split_word_into_chars($word, $number_of_chars, $include_end_of_text = "yes")
{
    $number_of_chars = $number_of_chars / 3;

    $arr = str_split($word, 3);

    if(count($arr)<$number_of_chars){
        $number_of_chars=count($arr)-1;
    }

    $arr = array_slice($arr, 0, (int)$number_of_chars);


    if ($include_end_of_text == "yes") {
        $arr[] = " ...";
    }

    return implode("", $arr);
}

function split_word_into_chars_ar($word,$number_of_chars,$include_end_of_text=true)
{
    $word = strip_tags($word);

    mb_internal_encoding("UTF-8"); // this IS A MUST!! PHP has trouble with multibyte

    $chars = array();
    for ($i = 0; $i < $number_of_chars; $i++ ) {
        $chars[] = mb_substr($word, $i, 1); // only one char to go to the array
    }


    if($include_end_of_text&&strlen($word)>$number_of_chars){
        $chars[]=" ...";
    }

    return implode("",$chars);
}

function splitWordIntoCharsWithTitle($word,$number_of_chars=10){

    return "<span title='".$word."'>".split_word_into_chars_ar($word,$number_of_chars,false)."</span>";

}


function capitalize_string($string){
    $field_name=explode("_",$string);
    if(isset_and_array($field_name)){
        $field_name=array_map("ucfirst",$field_name);
        return implode(" ",$field_name);
    }
    else{
        return ucfirst($field_name);
    }
}

function get_last_word_from_sentence($sentence){
    $sentence_arr=explode(" ",$sentence);
    if(is_array($sentence_arr)&&count($sentence_arr)){
        $last_word=$sentence_arr[count($sentence_arr)-1];
        unset($sentence_arr[count($sentence_arr)-1]);
        return [implode(" ",$sentence_arr),$last_word];
    }

    return [$sentence,$sentence];
}

function replaceNewLineWithBr($string){

    return str_replace("\n","<br>",$string);

}

function str_lreplace($search, $replace, $subject)
{
    $pos = strrpos($subject, $search);

    if($pos !== false)
    {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;
}
