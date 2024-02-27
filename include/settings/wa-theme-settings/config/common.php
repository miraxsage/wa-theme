<?php

use Carbon_Fields\Field;

trait WaThemeSettingsConfig{

    private $config = [];

    private function init_field_config($setting_type, $setting_name, $title, $description = "", $default = true, $opts = []){
        $this->config[$setting_name] = [
            "type" => $setting_type,
            "default" => $default,
            "title" => $title,
            "description" => $description,
            "options" => $opts
        ];
    }
    private function init_bool_field_config($setting_name, $title, $description = "", $default = true){
        $this->init_field_config("bool", $setting_name, $title, $description, $default);
    }
    private function init_text_field_config($setting_name, $title, $description = "", $default = ""){
        $this->init_field_config("text", $setting_name, $title, $description, $default);
    }
    private function init_textarea_field_config($setting_name, $title, $description = "", $default = ""){
        $this->init_field_config("textarea", $setting_name, $title, $description, $default);
    }
    private function init_schema_field_config($setting_name, $title, $description, $default, $mode = "scope"){
        $opts = ["mode" => $mode];
        $this->init_field_config("schema", $setting_name, $title, $description, $default, $opts);
    }
    private function init_toggle_list_field_config($setting_name, $title, $description, $toggles, $default = null){
        if(empty($default)) {
            $default = "";
            foreach($toggles as $k => $v)
                $default .= (empty($default) ? "" : ",").(is_numeric($k) ? $v : $k).":".(empty($default) ? 1 : 0);
        }
        $choices = [];
        foreach($toggles as $k => $v){
            if(is_numeric($k))
                $choices[$v] = $v;
            else
                $choices[$k] = $v;
        }
        $this->config[$setting_name] = [
            "type" => "toggle_list",
            "default" => $default,
            "title" => $title,
            "description" => $description,
            "choices" => $choices
        ];
    }

    private function init_config(){
        // Конфигурация общих настроек
        // Блок "Хлебные крошки"
        $this->init_bool_field_config("common_breadcrumbs__show_current_page", "Текущая страница",
            "Включать открытую страницу в список хлебных крошек");

        $this->init_bool_field_config("common_breadcrumbs__use_main_page_icon", "Иконка \"Главной\"",
            "Использовать иконку вместо надписи \"Главная\"");

        $this->init_bool_field_config("common_breadcrumbs__use_nav", "Использовать nav-контейнер",
            "Использовать тег nav в качестве контейнера блока хлебных крошек");

        $this->init_bool_field_config("common__pagination_use_nav", "Nav-контейнер для пагинации",
            "Использовать тег nav в качестве контейнера блока пагинации");

        $this->init_bool_field_config("common__expand_content_titles", "Разворачивать содержание",
            "Разворачивать список содержания текущего поста при его открытии");

        $this->init_textarea_field_config("common__code_at_head_end", "Код перед закрытием \"head\"",
            "HTML-код, добавляемый перед закрытием тега </head> на каждой странице");

        $this->init_textarea_field_config("common__code_at_body_end", "Код перед закрытием \"body\"",
            "HTML-код, добавляемый перед закрытием тега </body> на каждой странице");

        // Блок "Сайдбары"
        //
        $dcf = '{"visible":true, "lines": [{"width":null,"height":null,"margin":"- - - -","padding":"- - - -","bg":null,"topBorder":null,"bottomBorder":null,"items":[]}]}';
        $this->config["common__sidebars_configuration"] = [
            "type" => "sidebars_configuration",
            "default" => sprintf('[{"profile": "Общий", "key": "common", "filter": {"posts": {"mode": "all", "ids": []}, "pages": {"mode": "all", "ids": []}, "cats": {"mode": "all", "ids": []}}, "config":    {"header":%1$s,"footer":%1$s,"inner_left":%1$s,"inner_right":%1$s,"outer_left":%1$s,"outer_right":%1$s}    }]', $dcf),
            "title" => "Конфигурация и содержание блоков сайдбаров",
            "description" => "Укажите видимость отдельных сайдбаров, их конфигурацию и наполнение содержимым (виджетами)"
        ];


        // Конфигурация настроек записей
        require("records.php");

        // Конфигурация настроек страниц
        require("pages.php");

        // Конфигурация настроек архивов
        require("archives.php");
    }

    private function choices_to_buttons($choices){
        $buttons = [];
        foreach($choices as $k => $v){
            $checked = true;
            if(is_array($v)){
                $checked = array_key_exists("checked", $v) && $v["checked"];
                $text = $v["label"];
            }
            else
                $text = $v;
            $buttons[$k] = ["act" => $checked, "text" => $text];
        }
        return $buttons;
    }
    private function schema_default_text($default_value){
        try{
            $default_value = (object)json_decode($default_value);
            return $default_value->text;
        }
        catch(Exception $exc) {

        }
        return "";
    }

    public function get_sync_setting_control($setting, $args = []){
        $control = $this->get_setting_control_initial($setting, $args, null);
        if(!empty($control) && $control instanceof \Custom_Carbon_Fields\Wa_Custom_Carbon) {
            $control->set_theme_syncronized(true);
        }
        return $control;
    }
    
    public function get_personal_setting_control($setting, $args = [], $customizer = null){
        $control = $this->get_setting_control_initial($setting, $args, null);
        if(!empty($control)) {
            $control->set_default_value(null);
            $control->set_is_personal_setting(true);
        }
        return $control;
    }

    public function get_setting_control($setting, $args = [], $customizer = null){
        $control = $this->get_setting_control_initial($setting, $args, $customizer);
        return $control;
    }

    private function get_setting_control_initial($setting, $args = [], $customizer = null){
        if(!array_key_exists($setting, $this->config))
            return null;
        $config = $this->config[$setting];
        $default = null;
        if(array_key_exists("default", $config))
            $default = $config["default"];
        if(isset($config["control"]) && is_callable($config["control"])){
            $control = $config["control"]($setting, $args, $customizer);
            if(!empty($control))
                return $control;
        }
        if(empty($customizer)){
            if($config["type"] == "bool")
                return Field::make('wa_toggle', $setting, $config["title"])
                    ->set_default_value($default ?? true)
                    ->set_description($config["description"]);
            elseif($config["type"] == "text")
                return Field::make('wa_text', $setting, $config["title"])
                    ->set_default_value($default ?? "")
                    ->set_description($config["description"]);
            elseif($config["type"] == "textarea")
                return Field::make('wa_text', $setting, $config["title"])
                    ->set_default_value($default ?? "")
                    ->set_textarea_mode(true)
                    ->set_description($config["description"]);
            elseif($config["type"] == "schema") {
                $mode = "scope";
                if(array_key_exists("options", $config) &&
                    array_key_exists("mode", $config["options"]) &&
                    $config["options"]["mode"] == "property")
                    $mode = "property";
                return Field::make('wa_schema_info', $setting, $config["title"])
                    ->set_default_value($default ?? '{"act":false,"text":""}')
                    ->set_mode($mode)
                    ->set_default_text($this->schema_default_text($config["default"]))
                    ->set_description($config["description"]);
            }
            elseif($config["type"] == "toggle_list" || $config["type"] == "check_list") {
                $buttons = $this->choices_to_buttons($config["choices"]);
                return Field::make('wa_toggle_button_list', $setting, $config["title"])
                    ->set_mode($config["type"] == "toggle_list" ? "radio" : "check")
                    ->set_default_value($default ?? array_key_first($buttons))
                    ->set_description($config["description"])
                    ->set_buttons($buttons);
            }
            elseif($config["type"] == "sortable_toggle_list")
                return Field::make('wa_advanced_pill_list', $setting, $config["title"])
                    ->set_description($config["description"])
                    ->set_default_value($default ?? "")
                    ->set_choices($config["choices"]);
            elseif($config["type"] == "sidebars_configuration")
                return Field::make('wa_sidebars_configuration', $setting, $config["title"])
                    ->set_description($config["description"])
                    ->set_default_value($default ?? "{}");
        }
        else{
            if($config["type"] == "bool")
                return new WebArch_Toggle_Switch_Custom_Control($customizer, $setting,
                    array_merge($args,
                        [
                            'label' => $config["title"],
                            'description' => $config["description"]
                        ]));
            elseif($config["type"] == "text")
                return new WebArch_Custom_Control($customizer, $setting,
                    array_merge($args,
                        [
                            'label' => $config["title"],
                            'type' => 'text'
                        ]));
            elseif($config["type"] == "textarea")
                return new WebArch_Custom_Control($customizer, $setting,
                    array_merge($args,
                        [
                            'label' => $config["title"],
                            'description' => $config["description"],
                            'input_attrs' => ["placeholder" => $config["default"]],
                            'type' => 'textarea'
                        ]));
            elseif($config["type"] == "schema"){
                $placeholder = ((array)json_decode($config["default"]))["text"];
                return new WebArch_Toggle_Switch_Text_Custom_Control($customizer, $setting,
                    array_merge($args,
                        [
                            'label' => $config["title"],
                            'placeholder' => $placeholder
                        ]));
            }
            elseif($config["type"] == "check_list")
                return new WebArch_Pill_Checkbox_Custom_Control($customizer, $setting,
                    array_merge($args,
                        [
                            'label' => $config["title"],
                            'description' => $config["description"],
                            'choices' => $config["choices"]
                        ]));
            elseif($config["type"] == "toggle_list")
                return new WebArch_Text_Radio_Button_Custom_Control($customizer, $setting,
                    array_merge($args,
                        [
                            'label' => $config["title"],
                            'description' => $config["description"],
                            'choices' => $config["choices"]
                        ]));
            elseif($config["type"] == "sortable_toggle_list")
                return new WebArch_Pill_Checkbox_Advanced_Custom_Control($customizer, $setting,
                    array_merge($args,
                        [
                            'label' => $config["title"],
                            'description' => $config["description"],
                            'input_attrs' => array('sortable' => true, 'fullwidth' => true),
                            'choices' => $config["choices"]
                        ]));
        }
        return null;
    }
}