<?php

trait WaThemeSettingsConfig{

    private $config = [];

    private function init_field_config($setting_type, $setting_name, $title, $description = "", $default = true){
        $this->config[$setting_name] = [
            "type" => $setting_type,
            "default" => $default,
            "title" => $title,
            "description" => $description
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
    private function init_schema_field_config($setting_name, $title, $description, $default){
        $this->init_field_config("schema", $setting_name, $title, $description, $default);
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

        // Конфигурация общих настроек записей
        require("records.php");
    }
}