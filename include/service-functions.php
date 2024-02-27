<?php
function str_replace_first($haystack, $needle, $replace, &$changed = false){
    $changed = false;
    $pos = mb_strpos($haystack, $needle);
    if ($pos !== false) {
        $changed = true;
        return mb_substr_replace($haystack, $replace, $pos, strlen($needle));
    }
    return $haystack;
}
function mb_substr_replace($original, $replacement, $position, $length)
{
    $startString = mb_substr($original, 0, $position, "UTF-8");
    $endString = mb_substr($original, $position + $length, mb_strlen($original), "UTF-8");

    $out = $startString . $replacement . $endString;

    return $out;
}
function arr_has($arr, $key, $val = null){
    if(empty($arr) || !is_array($arr) || empty($key) || !key_exists($key, $arr))
        return false;
    if($val == null)
        return true;
    return $arr[$key] == $val;
}
function declention($num, $var0, $var1, $var2){
    $num = floatval($num);
    if(round($num / 10.0, 0) == 1 || $num % 10 == 0)
        return $var0;
    elseif($num % 10 == 1)
        return $var1;
    elseif(($num % 10 >= 2) && ($num % 10 <= 4))
        return $var2;
    return $var0;
}
