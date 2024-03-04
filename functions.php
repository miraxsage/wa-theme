<?php
define("WA_THEME_URI", get_template_directory_uri()."/");
define("WA_JS_URI", WA_THEME_URI."assets/js/");
define("WA_CSS_URI", WA_THEME_URI."assets/css/");
define("WA_IMG_URI", WA_THEME_URI."assets/img/");
define("WA_THEME_DIR", get_template_directory()."/");
define("WA_JS_DIR", WA_THEME_DIR."assets/js/");
define("WA_CSS_DIR", WA_THEME_DIR."assets/css/");
define("WA_IMG_DIR", WA_THEME_DIR."assets/img/");

require_once("vendor/carbon-fields/carbon-fields-plugin.php");
require_once("include/service_functions.php");
require_once("include/post_views_counter.php");
require_once("include/settings/settings.php");
require_once("include/main-category.php");
require_once("include/post-headings.php");
require_once("include/social-buttons.php");

//Reset to defaults
// add_action("carbon_fields_register_fields", function(){
//    carbon_set_theme_option("common__profiled_settings", WaThemeSettings::get()->get_setting_default("common__profiled_settings"));
//    set_theme_mod("common__profiled_settings", $def);
// });

add_filter("redirect_term_location", function($url, $tax){
    $args = [];
    foreach($_REQUEST as $key => $val){
        if(str_starts_with($key, "wa-carbon-active-tab__"))
            $args[] = $key . "=" . $val;
    }
    $url .= (str_contains($url, "&") ? "&" : "?") . join("&", $args);
    //file_put_contents(__DIR__ . "/log.txt", $url);
    return $url;
}, 10, 2);

add_action("after_setup_theme", function() {
    add_theme_support("post-thumbnails");
    setlocale(LC_ALL, wa_blog_locale().".".get_bloginfo("charset"));
});

add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'dashicons' );
} );

add_action( 'admin_enqueue_scripts', function() {
    wp_enqueue_style('wa_admin_styles', WA_CSS_URI."admin-style.css");
} );

function wa_blog_locale() : string {
    $lang = get_bloginfo("language");
    if(preg_match("/[a-z]+[-_][A-Z]+/", $lang, $matches) === 1)
        return str_replace("-", "_", $matches[0]);
    return "ru_RU";
}

function wa_template($slug, $name = null, $args = null): void {
    get_template_part($slug, $name, $args);
}
function wa_subtemplate($slug, $name = null, $args = null): void {
    get_template_part("template-parts/".$slug, $name, $args);
}
function wa_get_date_square($date, $type = "usual"): string {
    if(is_string($date))
        $date = strtotime($date);
    $m = date("%b", $date);
    $m = mb_strtoupper(mb_substr($m, 0, 1)).mb_strtolower(mb_substr($m, 1));
    $d = date("d", $date);
    $y = date("Y", $date);
    $classes = "wa-date-square" . ($type == "small" ? " wa-date-small" : "");
    return <<<CODE
<div class="$classes">
    <span class="wa-date-month">$m</span>
    <span class="wa-date-day">$d</span>
    <span class="wa-date-year">$y</span>
</div>
CODE;
}
function wa_the_date_square($date, $type = "usual"): void {
    echo wa_get_date_square($date, $type);
}
function wa_get_post($the_post){
    if(!empty($the_post)){
        if($the_post instanceof \WP_Post) {
            return $the_post;
        }
        elseif (preg_match('/^\d+$/', strval($the_post)) === 1)
            $the_post = get_post(intval($the_post));
        else
            $the_post = null;
    }
    global $post;
    if(empty($the_post))
        return $post;
    return $the_post;
}

function wa_get_post_thumbnail($post, $size = "full", $attrs = null) : string{
    if(empty($post = wa_get_post($post)))
        return "";
    $attrs_string = "";
    if(!empty($attrs) && is_array($attrs)){
        if(!key_exists("class", $attrs))
            $attrs["class"] = "wa-wide-image";
        if(!key_exists("itemprop", $attrs))
            $attrs["itemprop"] = "image";
        foreach ($attrs as $k => $v){
            if(!is_numeric($k) && is_string($v) && !empty($v))
                $attrs_string .= (empty($attrs_string) ? "" : " ").esc_attr($k)."=\"".esc_attr($v)."\"";
        }
    }
    $url = WA_IMG_URI."post-default-thumbnail.jpg";
    $caption = "Превью поста";
    if(has_post_thumbnail($post->ID)) {
        $url = get_the_post_thumbnail_url($post, $size);
        $caption = get_post_meta(get_post_thumbnail_id($post), '_wp_attachment_image_alt', true);
    }
    return <<<CODE
    <img alt="$caption" src="$url" $attrs_string>
CODE;
}
function wa_the_post_thumbnail($post, $size = "full") : void {
    echo wa_get_post_thumbnail($post, $size);
}
function get_queried_category(){
    $cat = get_query_var('cat');
    if(empty($cat))
        return null;
    $cat = get_category($cat);
    if(empty($cat) || $cat instanceof WP_Error)
        return null;
    return $cat;
}
function get_queried_category_id($default = null){
    $cat = get_queried_category();
    if(empty($cat))
        return $default;
    return $cat->term_id;
}
function wa_get_category_description(){
    $category = get_queried_category();
    if(!empty($category))
        return $category->category_description;
    return null;
}
function wa_the_category_description(){
    $category_descr = wa_get_category_description();
    if(!empty($category_descr))
        echo sprintf('<div class="wa-archive-description">%s</div>', $category_descr);
}
function wa_get_post_record_header($post, $record_type = "similar"){
    if(empty($post = wa_get_post($post)))
        return "";
    $settings = WaThemeSettings::get();
    $smlr = $record_type == "similar";
    $arch = $record_type == "archive";
    $classes = $arch ? "wa-archive-img" : "wa-related-image";
    $img_attrs = $arch ? ["loading" => "lazy"]  : [];
    $date_template = ($record_type == "similar" ? $settings->similar_record_date_in_square : $settings->archive__date_in_square) ?
                        wa_get_date_square($post->post_date, $smlr ? "small" : "usual") : "";
    $img_template = wa_get_post_thumbnail($post, $smlr ? "medium" : "large", $img_attrs);
    $post_link = get_post_permalink($post->ID);
    return <<<CODE
        <div class="$classes">
            $date_template
            <a href="$post_link">
                $img_template
            </a>
        </div>
CODE;
}
function wa_the_post_record_header($post, $record_type = "similar"){
    echo wa_get_post_record_header($post, $record_type);
}

function wa_the_archive_title(){
    $title = "Все записи блога";
    $qobj = get_queried_object();
    if($qobj instanceof WP_Term)
        $title = $qobj->name;
    echo "<".semantics_tag("archive_semantics__archive_headline").">$title</".semantics_tag("archive_semantics__archive_headline").">";
}
function reading_time($post) {
    if(empty($post = wa_get_post($post)))
        return "";
    $content = get_post_field('post_content', $post->ID);
    $content_clean = strip_tags($content);
    $word_count = count(preg_split('/\s+/', $content_clean));
    $readingtime = ceil($word_count / 200);
    return $readingtime." минут".declention($readingtime, "", "а", "ы");
}

function schema_item($item, $pref = "", $suff = ""){
    return WaThemeSettings::get()->schema_item($item, $pref, $suff);
}
function semantics_tag($type){
    return WaThemeSettings::get()->semantics_tag($type);
}
function setting($setting){
    return WaThemeSettings::get()->__get($setting);
}
function extract_margin($margin_raw){
    if(empty($margin_raw))
        return null;
    if(preg_match("/^(-?\d+(\.\d+)?|-) (-?\d+(\.\d+)?|-) (-?\d+(\.\d+)?|-) (-?\d+(\.\d+)?|-)$/", $margin_raw) !== 1)
        return null;
    $els = explode(" ", $margin_raw);
    $res = "";
    $props = ["top", "right", "bottom", "left"];
    for($i = 0; $i < 4; $i++){
        if($els[$i] == "-")
            continue;
        $res .= "margin-".$props[$i].":".$els[$i]."px;";
    }
    if(empty($res))
        return null;
    return substr($res, 0, strlen($res) - 1);
}
function extract_border($border_raw){
    if(empty($border_raw))
        return null;
    if(preg_match("/^\d+(\.\d+)?,#[0-9a-f]{6}$/", $border_raw) !== 1)
        return null;
    $els = explode(",", $border_raw);
    return $els[0]."px solid ".$els[1];
}
function extract_sidebar_line_css_props($line, $asString = true, $without = []){
    if(empty($line))
        return null;
    $props = [];
    $props["width"] = array_key_exists("width", $line) && !in_array("width", $without) ? $line["width"] : null;
    $props["height"] = array_key_exists("height", $line) && !in_array("height", $without) ? $line["height"] : null;
    $props["padding"] = array_key_exists("padding", $line) && !in_array("padding", $without) ? str_replace("margin", "padding", extract_margin($line["padding"]) ?? "") : null;
    $props["margin"] = array_key_exists("margin", $line) && !in_array("margin", $without) ? extract_margin($line["margin"]) : null;
    $props["background"] = array_key_exists("bg", $line) && !in_array("background", $without) ? $line["bg"] : null;
    $props["border-top"] = array_key_exists("topBorder", $line) && !in_array("border-top", $without) ? extract_border($line["topBorder"]) : null;
    $props["border-bottom"] = array_key_exists("bottomBorder", $line) && !in_array("border-bottom", $without) ? extract_border($line["bottomBorder"]) : null;
    if($asString){
        $res = "";
        foreach ($props as $k => $v)
            $res .= (!empty($res) && !str_ends_with($res, ";")  ? ";" : "").(empty($v) ? "" : (preg_match("/^(margin|padding)$/", $k) === 1 ? $v : $k.":".$v));
        return $res;
    }
    return $props;
}
function sidebar_config($sidebar){
    $config = WaThemeSettings::get()->sidebar_config($sidebar);

    if(empty($config) || !$config["visible"])
        return null;
    $has_elements = false;
    for($i = 0; $i < count($config["lines"]); $i++){
        $line = $config["lines"][$i];
        for($j = 0; $j < count($line["items"]); $j++){
            $has_elements = true;
            break 2;
        }
    }
    if(!$has_elements)
        return null;
    return $config;
}
function output_sidebar($config, $class = "wa-sidebar-content", $horizontal = false){

    if(gettype($config) == "string")
        $config = sidebar_config($config);
    if(empty($config) || !is_array($config) || !array_key_exists("lines", $config) || (!array_key_exists("visible", $config) || !$config["visible"]))
        return;
    $lines_html = "";
    foreach($config["lines"] as $line){
        $line_html = "";
        foreach($line["items"] as $item){
            if(!empty($item["html"]))
                $line_html .= "<div>".$item["html"]."</div>";
        }
        if(!empty($line_html)) {
            $line_html = apply_filters( 'the_content', $line_html );
            $line_html = str_replace( ']]>', ']]&gt;', $line_html );
            $lines_html .= "<div class='wa-sidebar-line' style=\"" . ($horizontal ? extract_sidebar_line_css_props($line, true) : "") . "\">" . $line_html . "</div>";
        }
    }
    if(!empty($lines_html))
        echo "<div class=\"$class\"><div class='wa-sidebar-lines".($horizontal ? " wa-sidebar-lines-horizontal" : "")."'>".$lines_html."</div></div>";
}
function check_post_comments_personal_agreement($comment_post_id) {
    if(is_user_logged_in())
        return;
    $post_type = get_post_type($comment_post_id);
    if($post_type == "page" ? WaThemeSettings::get()->page_comments__personal_agreement :
        (!empty($post_type) && WaThemeSettings::get()->record_comments__personal_agreement)) {
        if (!isset($_REQUEST["wp-comment-personal-agreement"]) || $_REQUEST["wp-comment-personal-agreement"] != "yes") {
            wp_die(__('<strong>Ошибка:</strong> Для отправки комментария подтвердите свое согласие на обработку персональных данных.
                       <p><a href="javascript:history.go(-1)">← Назад</a></p>'));
        }
    }
}
add_action('pre_comment_on_post', 'check_post_comments_personal_agreement');