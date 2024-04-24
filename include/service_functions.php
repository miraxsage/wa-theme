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
    if($val === true && !empty($arr[$key]))
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
function wa_queried_target($non_stardart_check = true){
    if(is_archive() || ($non_stardart_check && !empty($GLOBALS["wa_is_archive_template_render"])))
        return "archive";
    $qobj = wa_queried_object();
    if($qobj instanceof WP_Post){
        if(get_post_type($qobj->ID) == "page")
            return "page";
        else
            return "record";
    }
    return "";
}
function wa_queried_object(){
    $obj = get_queried_object();
    if(!empty($obj))
        return $obj;
    global $pagenow;
    global $post;
    if(in_array($pagenow, array('post.php', 'post-new.php')))
        return $post;
    return null;
}
function wa_is_single(){
    $target = wa_queried_target();
    return $target == "page" || $target == "record";
}
function wa_is_page(){
    return wa_queried_target() == "page";
}
function wa_is_record(){
    return wa_queried_target() == "record";
}
function wa_is_archive($common = true){
    return wa_queried_target($common) == "archive";
}
$is_archive = wa_is_archive();
$is_record = false;
$is_page = false;
if(is_single()) {
    $qobj = get_queried_object();
    if($qobj instanceof WP_Post){
        if(get_post_type($qobj->ID) == "page")
            $is_page = true;
        else
            $is_record = true;
    }
}

function similar_posts($post_id){
    if(empty($post_id))
        return [];
    if($post_id instanceof WP_Post){
        $post = $post_id;
        $post_id = $post->ID;
    }
    else
        $post = get_post($post_id);
    global $wpdb;
    $cats = wp_get_post_categories($post_id);
    $tags_objs = wp_get_post_tags($post_id);
    $tags = [];
    foreach ($tags_objs as $tobj)
        $tags[] = $tobj->term_id;
    $terms_ids = join(",", [...$cats, ...$tags]);
    if(empty($terms_ids))
        $terms_ids = "-1";
    preg_match_all("/[a-zа-я0-9]+/ui", $post->post_title, $matches, PREG_SET_ORDER);
    $rel_cond = "";
    foreach($matches as $m) {
        if(mb_strlen($m[0]) > 2)
            $rel_cond .= (empty($rel_cond) ? "" : " + ") . "(case when post_title like '%$m[0]%' then 1 else 0 end)";
    }
    if(empty($rel_cond))
        $rel_cond = "0";
    $similars = $wpdb->get_results(sprintf('select * from
    (
        select 
            p.id, 
            p.relevance, 
            sum(case when rel.object_id is not null then 1 else 0 end) as terms_relevance
        from 
        (
            select id,
                   %s as relevance	
            from '.$wpdb->posts.'
                where post_type = "%s" and ID <> %s
    
        ) as p
        left join '.$wpdb->term_relationships.' as rel
            on rel.object_id = p.id and rel.term_taxonomy_id in (%s) 
        group by p.id, p.relevance
    ) as q
    where q.relevance > 0 or q.terms_relevance > 0
    order by q.relevance desc, q.terms_relevance desc, q.id desc
    limit 0, 3', $rel_cond, $post->post_type, $post_id, $terms_ids));
    if(empty($similars))
        return [];
    $ids = [];
    foreach ($similars as $s)
        $ids[] = $s->id;
    return get_posts(['post__in' => $ids]);
}
