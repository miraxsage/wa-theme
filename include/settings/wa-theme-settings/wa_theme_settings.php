<?php

use Carbon_Fields\Field;

require_once("config/common.php");

class WaThemeSettings{

    use WaThemeSettingsConfig;

    // this field is set up by external code to fill "changeset" values going from customizer
    // this external code block is in settings.php:
    //add_action('customize_preview_init', function(WP_Customize_Manager $customizer){
    //    WaThemeSettings::$changeset = [];
    //    foreach ($customizer->settings() as $id => $setting) {
    //        WaThemeSettings::$changeset[$id] = $setting->value();
    //    }
    //});
    public static $changeset;

    private static $settings_instance;
    private $cache = [];
    private $theme_mods;
    public static function get(){
        if(empty(WaThemeSettings::$settings_instance))
            WaThemeSettings::$settings_instance = new WaThemeSettings();
        return WaThemeSettings::$settings_instance;
    }

    public function __construct(){
        $this->theme_mods = get_theme_mods();
        $this->init_config();
    }

    public function reset_settings(){
        foreach ($this->config as $setting => $config) {
            remove_theme_mod($setting);
        }
    }

    public function set_setting($name, $value){
        if($value ==  "true")
            $value = 1;
        if($value == "false")
            $value = 0;
        set_theme_mod($name, $value);
    }

    public function get_setting_default($setting, $default = null, $raw = true){
        if(array_key_exists($setting, $this->config))
        {
            if(array_key_exists("default", $this->config[$setting])) {
                if($raw)
                    return $this->config[$setting]["default"];
                return $this->extract_raw_value($this->config[$setting]["default"], $setting);
            }
        }
        return $default;
    }

    public function get_setting($name, $raw = true, $ignore_personal = false, $ignore_parent = false){
        if(wa_is_page())
            $name = preg_replace("/^record_/", "page_", $name);
        if(wa_is_record())
            $name = preg_replace("/^page_/", "record_", $name);

        if(!array_key_exists($name, $this->config))
            return null;

        $config = $this->config[$name];

        //retrieving personal settings
        $val = null;
        if($ignore_personal !== true) {
            if(str_starts_with($name, "archive")) {
                $queried_cat = get_queried_category();
                if (!empty($queried_cat)) {
                    if ($queried_cat instanceof WP_Term) {
                        $val = carbon_get_term_meta($queried_cat->term_id, $name);
                        if(str_ends_with($name, "__profiled_settings") && $this->get_setting_default($name) == $val)
                            $val = null;
                    }
                }
            }
            elseif(str_starts_with($name, "record_") || str_starts_with($name, "page_")) {
                $queried_obj = get_queried_object();
                if (!empty($queried_obj)) {
                    if ($queried_obj instanceof WP_Post) {
                        $val = carbon_get_post_meta($queried_obj->ID, $name);
                        if(str_ends_with($name, "__profiled_settings") && $this->get_setting_default($name) == $val)
                            $val = null;
                    }
                }
            }
        }


        //retrieving parent page / category settings
        $search_further = $val === null || $val === "[!discard personal]";
        $empty_text = ($config["type"] == "text" || $config["type"] == "textarea") && $val === "";
        if($ignore_parent !== true && (str_starts_with($name, "record_") || str_starts_with($name, "page_")) && ($search_further || $empty_text)) {
            $queried_obj = wa_queried_object();
            if (!empty($queried_obj) && $queried_obj instanceof WP_Post) {
                $post_type = get_post_type($queried_obj->ID);
                if($post_type == "page"){
                    $name = preg_replace("/^record_/", "page_", $name);
                    if(!empty($queried_obj->post_parent)){
                        $parent_page = get_post($queried_obj->post_parent);
                        if(!empty($parent_page))
                            $val = carbon_get_post_meta($parent_page->ID, $name);
                    }
                }
                elseif($post_type == "post"){
                    $name = preg_replace("/^page_/", "record_", $name);
                    $wp_main_cat = get_post_meta($queried_obj->ID, 'wa_main_category', true);
                    if(!empty($wp_main_cat)) {
                        $category = get_category($wp_main_cat);
                        if(!empty($category)){
                            $val = carbon_get_term_meta($category->cat_ID, $name);
                        }
                    }
                }
            }
        }

        //retrieving parent page / category settings by pointed parent (category or page instead of "ignore_parent")
        $search_further = $val === null || $val === "[!discard personal]";
        $empty_text = ($config["type"] == "text" || $config["type"] == "textarea") && $val === "";
        if(!empty($ignore_parent) && (str_starts_with($name, "record_") || str_starts_with($name, "page_")) && ($search_further || $empty_text)) {
            if(is_numeric($ignore_parent) && str_starts_with($name, "record_")){
                $val = carbon_get_term_meta($ignore_parent, $name);
            }
            elseif(is_numeric($ignore_parent) && str_starts_with($name, "page_")){
                $val = carbon_get_post_meta($ignore_parent, $name);
            }
        }

        $search_further = $val === null || $val === "[!discard personal]";
        $empty_text = ($config["type"] == "text" || $config["type"] == "textarea") && $val === "";
        //retrieving common settings
        if($search_further || $empty_text) {
            if (array_key_exists($name, $this->cache))
                $val = $this->cache[$name];
            elseif (!empty(self::$changeset) && array_key_exists($name, self::$changeset))
                $val = self::$changeset[$name];
            elseif (array_key_exists($name, $this->theme_mods)) {
                $val = $this->theme_mods[$name];
                if(($config["type"] == "text" || $config["type"] == "textarea") && $val == "")
                    $val = $this->get_setting_default($name, null, true);
            }
            else
                $val = $this->get_setting_default($name, null, true);
        }
        if($raw)
            return $val;
        return $this->extract_raw_value($val, $name);
    }

    public function get_raw_setting($name){
        return $this->get_setting($name, true);
    }

    public function extract_raw_value($raw_value, $setting){
        if(!is_string($raw_value))
            return $raw_value;
        if(!array_key_exists($setting, $this->config))
            return $raw_value;
        $config = $this->config[$setting];
        if($config["type"] == "bool")
            return $raw_value == true;
        if($config["type"] == "schema"){
            $schema = (object)json_decode($raw_value);
            if($schema->act)
                return $schema->text;
            return  "";
        }
        if($config["type"] == "check_list" || $config["type"] == "sortable_toggle_list"){
            $result = [];
            $pairs = explode(",", $raw_value);
            foreach ($pairs as $pair){
                $keyval = explode(":", $pair);
                $result[$keyval[0]] = $keyval[1] == 1;
            }
            return $result;
        }
        if($config["type"] == "toggle_list"){
            $pairs = explode(",", $raw_value);
            foreach ($pairs as $pair){
                $keyval = explode(":", $pair);
                if($keyval[1] == 1)
                    return $keyval[0];
            }
        }
        return $raw_value;
    }

    public function __get($name){
        return $this->get_setting($name, false);
    }
    function check_sidebar_config_profile_match($profile){
        $profile_filter = $profile["filter"];
        if(in_array("home", $profile_filter["pages"]["ids"]) && is_home())
            return true;
        if(in_array("search", $profile_filter["cats"]["ids"]) && is_search())
            return true;
        if(wa_is_single()) {
            $qobj = get_queried_object();
            if($qobj instanceof WP_Post){
                $type = get_post_type($qobj->ID) == "page" ? "pages" : "posts";
                if($profile_filter[$type]["mode"] == "all")
                    return true;
                $include = in_array($qobj->ID, $profile_filter[$type]["ids"]);
                if($include ? $profile_filter[$type]["mode"] == "include" : $profile_filter[$type]["mode"] == "exclude")
                    return true;
            }
        }
        else if(wa_is_archive()){
            if($profile_filter["cats"]["mode"] == "all")
                return true;
            $queried_cat = get_queried_category();
            if (!empty($queried_cat)) {
                if ($queried_cat instanceof WP_Term) {
                    $include = in_array($queried_cat->term_id, $profile_filter["cats"]["ids"]);
                    if($include ? $profile_filter["cats"]["mode"] == "include" : $profile_filter["cats"]["mode"] == "exclude")
                        return true;
                }
            }
        }
        return $profile_filter["pages"]["mode"] == "all" && $profile_filter["posts"]["mode"] == "all" && $profile_filter["cats"]["mode"] == "all";
    }
    public function sidebar_config($sidebar = null){
        $default_full_config = $this->get_setting_default("common__profiled_settings");
        $full_config = $this->get_setting("common__profiled_settings", true);
        if(empty($full_config))
            $full_config = $default_full_config || null;
        if(empty($full_config) || empty($sidebar))
            return null;
        $full_config = json_decode($full_config, true);
        foreach ($full_config as $c){
            if($this->check_sidebar_config_profile_match($c)){
                $config = $c["config"];
                break;
            }
        }
        if(!empty($config) && array_key_exists($sidebar, $config))
            return $config[$sidebar];
        return null;
    }
    public function schema_item($item, $prefix = "", $suffix = "", $parent_obj_id = null){
        $is_archive = wa_is_archive();
        $is_record = false;
        $is_page = false;
        if(wa_is_single()) {
            $qobj = get_queried_object();
            if($qobj instanceof WP_Post){
                if(get_post_type($qobj->ID) == "page")
                    $is_page = true;
                else
                    $is_record = true;
            }
        }
        if(!$is_archive && !$is_record && !$is_page)
            return "";
        $type = $is_archive ? "archive_schema__" : ($is_page ? "page_schema__" : "record_schema__");
        if($item == "body")
            $item = "page_body";
        if($item == "page_body" ||
            $item == "theme_header" ||
            $item == "theme_footer" ||
            preg_match("/^(inner|outer)_(left|right)_sidebar$/", $item) === 1)
            return $this->schema_item($type.$item, $prefix, $suffix);
        if($item == "article_container" || $item == "content_section" || $item == "h1" || $item == "about_author") {
            // для schema-атрибутов страницы отдельной записи, если они указаны на странице архива
            // выполняется подмена префикса архива на атрибут отдельной страницы и указание ее родителя -
            // категории текущей страницы архива, из которой они могут быть извлечены, если в
            // настройках данной категории установлены данные значения schema-атрибутов для записи
            return $this->schema_item($type.$item, $prefix, $suffix);
            return $this->schema_item(
                ($is_archive ? "record_schema__" : $type) . $item,
                $prefix,
                $suffix,
                $is_archive ? get_queried_category_id() : null);
        }
        $val = $this->get_setting($item, true, !empty($parent_obj_id), empty($parent_obj_id) ? false : $parent_obj_id);
        if(empty($val))
            return "";
        $val = (array)json_decode($val);
        if(empty($val) || !array_key_exists("act", $val) || !array_key_exists("text", $val) || $val["act"] !== true)
            return "";
        $template = $prefix.'itemscope itemtype="https://schema.org/%s"'.$suffix;
        $config = $this->config[$item];
        if($config && array_key_exists("options", $config) && array_key_exists("mode", $config["options"]) && $config["options"]["mode"] == "property")
            $template = $prefix.'itemprop="%s"'.$suffix;
        return sprintf($template, $val["text"]);
    }
    public function semantics_tag($type){
        $is_archive = wa_is_archive();
        $is_record = false;
        $is_page = false;
        if(wa_is_single()) {
            $qobj = get_queried_object();
            if($qobj instanceof WP_Post){
                if(get_post_type($qobj->ID) == "page")
                    $is_page = true;
                else
                    $is_record = true;
            }
        }
        if(!$is_archive && !$is_record && !$is_page)
            return "div";
        $target = $is_archive ? "archive_semantics__" : ($is_page ? "page_semantics__" : "record_semantics__");
        $common = ["theme_header", "theme_footer", "main_content", "inner_left_sidebar", "inner_right_sidebar", "outer_left_sidebar", "outer_right_sidebar", "page_header", "page_article", "page_headline", "page_headings"];
        if(in_array($type, $common)) {
            return $this->semantics_tag($target . $type);
        }
        $val = $this->get_setting($type, false);
        if(!empty($val))
            return $val;
        return "div";
    }
}