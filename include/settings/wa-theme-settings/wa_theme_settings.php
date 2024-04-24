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

    public function get_setting($name, $raw = true, $ignore_personal = false, $ignore_parent = false, $queried_obj = null){
        if($queried_obj ? $queried_obj instanceof WP_Post && get_post_type($queried_obj->ID) == "page" : wa_is_page())
            $name = preg_replace("/^(post|record)_/", "page_", $name);
        if($queried_obj ? $queried_obj instanceof WP_Post && get_post_type($queried_obj->ID) == "post" : wa_is_record())
            $name = preg_replace("/^(page|post)_/", "record_", $name);

        $is_profiled_setting = false;
        foreach(["page", "record", "archive"] as $target){
            foreach(["__blocks_sequence", "_schema__", "_semantics__"] as $sett){
                if(str_starts_with($name, $target.$sett)){
                    $is_profiled_setting = true;
                    break;
                }
            }
        }
        
        $config = null;
        if(!array_key_exists($name, $this->config)){
            if(!$is_profiled_setting)
                return null;
        }
        else
            $config = $this->config[$name];


        //retrieving personal settings
        $val = null;

        if($ignore_personal !== true) {
            // if(str_starts_with($name, "archive")) {
            //     $queried_cat = get_queried_category();
            //     if (!empty($queried_cat)) {
            //         if ($queried_cat instanceof WP_Term) {
            //             $val = carbon_get_term_meta($queried_cat->term_id, $name);
            //             if(str_ends_with($name, "__profiled_settings") && $this->get_setting_default($name) == $val)
            //                 $val = null;
            //         }
            //     }
            // }
            // else
            if(str_starts_with($name, "record_") || str_starts_with($name, "page_")) {
                $queried_obj = !empty($querieq_obj) ? $querieq_obj : get_queried_object();
                if (!empty($queried_obj)) {
                    if ($queried_obj instanceof WP_Post) {
                        $val = carbon_get_post_meta($queried_obj->ID, $name);
                        if(str_ends_with($name, "__profiled_settings") && $this->get_setting_default($name) == $val)
                            $val = null;
                    }
                }
            }
        }

        if(empty($val) && $is_profiled_setting){
            $profile = $this->profile_config(null, $queried_obj);


            $target = str_starts_with($name, "page") ? "pages" : (str_starts_with($name, "archive") ? "archives" : "posts");
            $setting_category = str_contains($name, "_schema_") ? "schema" : (str_contains($name, "_semantics_") ? "semantics" : null);
            $setting = str_replace(($target == "posts" ? "record" : substr($target, 0, -1))."_".($setting_category ? $setting_category."_" : "")."_", "", $name);
            $setting_array = $setting_category ? $profile["config"][$target][$setting_category] : $profile["config"][$target];

            if(array_key_exists($setting, $setting_array))
                $val = $setting_array[$setting];
        }

        // //retrieving parent page / category settings
        // $search_further = $val === null || $val === "[!discard personal]";
        // $empty_text = ($config["type"] == "text" || $config["type"] == "textarea") && $val === "";
        // if($ignore_parent !== true && (str_starts_with($name, "record_") || str_starts_with($name, "page_")) && ($search_further || $empty_text)) {
        //     $queried_obj = wa_queried_object();
        //     if (!empty($queried_obj) && $queried_obj instanceof WP_Post) {
        //         $post_type = get_post_type($queried_obj->ID);
        //         if($post_type == "page"){
        //             $name = preg_replace("/^record_/", "page_", $name);
        //             if(!empty($queried_obj->post_parent)){
        //                 $parent_page = get_post($queried_obj->post_parent);
        //                 if(!empty($parent_page))
        //                     $val = carbon_get_post_meta($parent_page->ID, $name);
        //             }
        //         }
        //         elseif($post_type == "post"){
        //             $name = preg_replace("/^page_/", "record_", $name);
        //             $wp_main_cat = get_post_meta($queried_obj->ID, 'wa_main_category', true);
        //             if(!empty($wp_main_cat)) {
        //                 $category = get_category($wp_main_cat);
        //                 if(!empty($category)){
        //                     $val = carbon_get_term_meta($category->cat_ID, $name);
        //                 }
        //             }
        //         }
        //     }
        // }

        // //retrieving parent page / category settings by pointed parent (category or page instead of "ignore_parent")
        // $search_further = $val === null || $val === "[!discard personal]";
        // $empty_text = ($config["type"] == "text" || $config["type"] == "textarea") && $val === "";
        // if(!empty($ignore_parent) && (str_starts_with($name, "record_") || str_starts_with($name, "page_")) && ($search_further || $empty_text)) {
        //     if(is_numeric($ignore_parent) && str_starts_with($name, "record_")){
        //         $val = carbon_get_term_meta($ignore_parent, $name);
        //     }
        //     elseif(is_numeric($ignore_parent) && str_starts_with($name, "page_")){
        //         $val = carbon_get_post_meta($ignore_parent, $name);
        //     }
        // }

        $search_further = !$is_profiled_setting && ($val === null || $val === "[!discard personal]");
        $empty_text = !$is_profiled_setting && ($config["type"] == "text" || $config["type"] == "textarea") && $val === "";
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
        if(str_ends_with($setting, "blocks_sequence") && preg_match("/^\s*{\s*\"sequence\"\s*:/", $raw_value) === 1){
            $val_obj = json_decode($raw_value, true);
            $sequence = $val_obj["sequence"];
            $sequence_arr = [];
            $pairs = explode(",", $sequence);
            foreach ($pairs as $pair){
                $keyval = explode(":", $pair);
                $sequence_arr[$keyval[0]] = ($keyval[1] == 1);
            }
            $val_obj["sequence"] = $sequence_arr;
            return $val_obj;
        }
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
    function check_profile_config_condition($current_obj, $condition){
        $cur_type = $current_obj["type"];
        $cur_obj = $current_obj["obj"];
        $cond_ids = $condition["ids"];
        $cond_inclusion = (str_starts_with($condition["mode"], "include") ? "include" : (str_starts_with($condition["mode"], "exclude") ? "exclude" : "all"));
        $cond_el = (str_ends_with($condition["mode"], "cats") ? "cat" : (str_ends_with($condition["mode"], "tags") ? "tag" : (str_ends_with($condition["mode"], "children") ? "child" : "element")));
        
        if($cond_inclusion == "all")
            return true;
        if($cur_type == "archive")
            return ($cur_obj == "home" || $cur_obj == "search") && in_array($cur_obj, $cond_ids);
        if($cur_type == "cat" || $cur_type == "tag")
            return $cur_type == $cond_el && in_array($cur_obj, $cond_ids);
        if($cond_el == "element"){
            if(in_array($cur_obj->ID, $cond_ids))
                return $cond_inclusion == "include";
            else
                return $cond_inclusion == "exclude";
        }
        else {
            if($cur_type == "post"){
                if($cond_el == "cat"){
                    $post_cats = wp_get_post_categories($cur_obj->ID);
                    foreach($post_cats as $post_cat_id){
                        if(in_array($post_cat_id, $cond_ids))
                            return true;
                    }
                }
                else if($cond_el == "tag"){
                    $post_tags = wp_get_post_tags($cur_obj->ID);
                    foreach($post_tags as $post_tag){
                        if(in_array($post_tag->term_id, $cond_ids))
                            return true;
                    }
                }
            }
            else if($cur_type == "page"){
                if($cond_el == "child" && in_array($cur_obj->post_parent, $cond_ids)){
                    return true;
                }
            }
        }
        return false;
    }
    function check_profile_config_match($profile, $queried_obj = null){
        if($profile["key"] == "common")
            return true;
        $current_obj = [];
        if(wa_is_single()) {
            $qobj = $queried_obj ? $queried_obj : get_queried_object();
            if($qobj instanceof WP_Post){
                $current_obj["type"] = get_post_type($qobj->ID) == "page" ? "page" : "post";
                $current_obj["obj"] = $qobj;
            }
            else
                return false;
        }
        else {
            if($queried_obj == "home" || is_home()){
                $current_obj["type"] = "archive";
                $current_obj["obj"] = "home";
            }
            else if($queried_obj == "search" || is_search()){
                $current_obj["type"] = "archive";
                $current_obj["obj"] = "search";
            }
            else {
                $queried_cat = ($queried_obj && get_category($queried_obj) ? $queried_obj : get_queried_category());
                if (!empty($queried_cat)) {
                    $current_obj["type"] = "cat";
                    $current_obj["obj"] = $queried_cat;
                }
                else {
                    $queried_tag = ($queried_obj && get_term($queried_obj) ? $queried_obj : get_queried_tag());
                    if (!empty($queried_tag)) {
                        $current_obj["type"] = "tag";
                        $current_obj["obj"] = $queried_tag;
                    }
                }
            }
        }
        $filter_type = ($current_obj["type"] == "page" ? "pages" : ($current_obj["type"] == "post" ? "posts" : "archives"));
        if(!$profile["filter"][$filter_type])
            return false;
        foreach($profile["filter"][$filter_type] as $condition){
            if($this->check_profile_config_condition($current_obj, $condition))
                return true;
        }
        return false;

        // $profile_filter = $profile["filter"];
        // if(in_array("home", $profile_filter["pages"]["ids"]) && is_home())
        //     return true;
        // if(in_array("search", $profile_filter["cats"]["ids"]) && is_search())
        //     return true;
        // if(wa_is_single()) {
        //     $qobj = get_queried_object();
        //     if($qobj instanceof WP_Post){
        //         $type = get_post_type($qobj->ID) == "page" ? "pages" : "posts";
        //         if($profile_filter[$type]["mode"] == "all")
        //             return true;
        //         $include = in_array($qobj->ID, $profile_filter[$type]["ids"]);
        //         if($include ? $profile_filter[$type]["mode"] == "include" : $profile_filter[$type]["mode"] == "exclude")
        //             return true;
        //     }
        // }
        // else if(wa_is_archive()){
        //     if($profile_filter["cats"]["mode"] == "all")
        //         return true;
        //     $queried_cat = get_queried_category();
        //     if (!empty($queried_cat)) {
        //         if ($queried_cat instanceof WP_Term) {
        //             $include = in_array($queried_cat->term_id, $profile_filter["cats"]["ids"]);
        //             if($include ? $profile_filter["cats"]["mode"] == "include" : $profile_filter["cats"]["mode"] == "exclude")
        //                 return true;
        //         }
        //     }
        // }
        // return $profile_filter["pages"]["mode"] == "all" && $profile_filter["posts"]["mode"] == "all" && $profile_filter["cats"]["mode"] == "all";
    }
    
    public function profile_config($predicate = null, $queried_obj = null){

        $default_full_config = $this->get_setting_default("common__profiled_settings");
        $full_config = $this->get_setting("common__profiled_settings", true);
        if(empty($full_config))
            $full_config = $default_full_config || null;
        if(empty($full_config))
            return null;
        $full_config = json_decode($full_config, true);
        $profile = null;
        foreach ($full_config as $c){
            if($this->check_profile_config_match($c, $queried_obj) && (!$predicate || $predicate($c))){
                $profile = $c;
                break;
            }
        }
        return $profile;
    }

    public function sidebar_config($sidebar = null){
        if(empty($sidebar))
            return null;
        $profile = $this->profile_config(function($profile){ return $profile["config"]["sidebars"]["active"]; });
        if(!empty($profile) && array_key_exists($sidebar, $profile["config"]["sidebars"]))
        return $profile["config"]["sidebars"][$sidebar];
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