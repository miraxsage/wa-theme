<?php

require("wa-theme-settings/wa_theme_settings.php");

//WaThemeSettings::get()->reset_settings();

add_action('customize_register', 'wa_customize_register');

function wa_customize_register(WP_Customize_Manager $customizer){

    require_once("custom-nesting-containers.php");
    require_once("custom-controls.php");

    $customizer->register_panel_type('WA_Customize_Panel');
    $customizer->register_section_type('WA_Customize_Section');

    $webarch_panel = new WA_Customize_Panel($customizer, 'webarch', array(
        'title' => 'WebArchitect',
        'description' => 'Уникальные параметры отображения темы WebArchitect',
    ));
    $customizer->add_panel($webarch_panel);

    require_once("init/customizer/common.php"); // инициализация элементов настройки вкладки "Общие" кастомизатора
    require_once("init/customizer/records.php"); // инициализация элементов настройки вкладки "Записи" кастомизатора
    require_once("init/customizer/pages.php"); // инициализация элементов настройки вкладки "Страницы" кастомизатора
    require_once("init/customizer/archives.php"); // инициализация элементов настройки вкладки "Страницы" кастомизатора
}

add_action('customize_preview_init', function(WP_Customize_Manager $customizer){
    WaThemeSettings::$changeset = [];
    foreach ($customizer->settings() as $id => $setting) {
        WaThemeSettings::$changeset[$id] = $setting->value();
    }
});

add_action("carbon_fields_register_fields", function(){
    // инициализация элементов управления общих настроек в админке,
    require_once("init/carbon-fields/common/common.php");
    require_once("init/carbon-fields/common/pages.php");
    require_once("init/carbon-fields/common/records.php");
    require_once("init/carbon-fields/common/archives.php");
    // а также персональных на соответсвующих страницах элементов (записи, страницы, архивы)
    require_once("init/carbon-fields/personal/page.php");
    require_once("init/carbon-fields/personal/record.php");
    require_once("init/carbon-fields/personal/category.php");
});