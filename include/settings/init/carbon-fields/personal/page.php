<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field\Field;

Container::make("post_meta", "wa_page_settings", "WebArchitect настройки отображения")
    ->where('post_type', '=', 'page')
    ->add_tab("Последовательность", [
        $settings->get_personal_setting_control("page__blocks_sequence"),
    ])
    //После введения профильных настроек непосредственно на странице поста / страницы 
    //можно настроить только последовательность блоков, которая переопределяет все остальные настройки
    // ->add_tab("Schema", [
    //     Field::make('wa_html', "page_schema__description")
    //         ->set_mode("description")
    //         ->set_html("Укажите значения Schema.org-атрибутов для соответствующих элементов каждой записи"),
    //     $settings->get_personal_setting_control("page_schema__page_body"),
    //     $settings->get_personal_setting_control("page_schema__theme_header"),
    //     $settings->get_personal_setting_control("page_schema__theme_footer"),
    //     $settings->get_personal_setting_control("page_schema__inner_left_sidebar"),
    //     $settings->get_personal_setting_control("page_schema__inner_right_sidebar"),
    //     $settings->get_personal_setting_control("page_schema__outer_left_sidebar"),
    //     $settings->get_personal_setting_control("page_schema__outer_right_sidebar"),
    //     $settings->get_personal_setting_control("page_schema__article_container"),
    //     $settings->get_personal_setting_control("page_schema__content_section"),
    //     $settings->get_personal_setting_control("page_schema__h1"),
    // ])
    // ->add_tab("Семантика", [
    //     Field::make('wa_html', "page_semantics__description")
    //         ->set_mode("description")
    //         ->set_html("Укажите значения вариантов исполнения для соответствующих элементов каждой записи"),
    //     $settings->get_personal_setting_control("page_semantics__theme_header"),
    //     $settings->get_personal_setting_control("page_semantics__theme_footer"),
    //     $settings->get_personal_setting_control("page_semantics__main_content"),
    //     $settings->get_personal_setting_control("page_semantics__inner_left_sidebar"),
    //     $settings->get_personal_setting_control("page_semantics__inner_right_sidebar"),
    //     $settings->get_personal_setting_control("page_semantics__outer_left_sidebar"),
    //     $settings->get_personal_setting_control("page_semantics__outer_right_sidebar"),
    //     $settings->get_personal_setting_control("page_semantics__page_header"),
    //     $settings->get_personal_setting_control("page_semantics__page_article"),
    //     $settings->get_personal_setting_control("page_semantics__page_headline"),
    //     $settings->get_personal_setting_control("page_semantics__page_headings")
    // ])
    ;





