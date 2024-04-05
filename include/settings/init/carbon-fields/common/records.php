<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field\Field;

Container::make("theme_options", "wa_records_settings", "Настройки записей")
    ->set_page_file("wa-records-settings.php")
    ->set_page_menu_title("Записи")
    ->set_page_parent("wa-settings.php")
    //После введения профильных настроек последовательность блоков, schema-артибуты и семантику можно 
    //настроить только в профилях + последовательность на каждой странице / после отдельно 
    // ->add_tab("Последовательность", [
    //     Field::make('wa_active_tab_saver', "records_active_tab_saver"),
    //     $settings->get_sync_setting_control("record__blocks_sequence"),
    // ])
    ->add_tab("Мета", [
        $settings->get_sync_setting_control("record_meta__use_icons"),
        $settings->get_sync_setting_control("record_meta__blocks_sequence"),
    ])
    ->add_tab("Общие", [
        $settings->get_sync_setting_control("record__img_outside"),
        $settings->get_sync_setting_control("record__date_in_square"),
        $settings->get_sync_setting_control("record__prev_next_links"),
        $settings->get_sync_setting_control("record__author_link_mode"),
        $settings->get_sync_setting_control("record__about_author_label"),
        $settings->get_sync_setting_control("record__similar_records_label"),
    ])
    ->add_tab("Комментарии", [
        $settings->get_sync_setting_control("record_comments__save_name"),
        $settings->get_sync_setting_control("record_comments__save_name_label"),
        $settings->get_sync_setting_control("record_comments__personal_agreement"),
        $settings->get_sync_setting_control("record_comments__personal_agreement_label")
    ])
    ->add_tab("Похожие записи", [
        $settings->get_sync_setting_control("record_similar__date_in_square"),
        $settings->get_sync_setting_control("record_similar__img_outside"),
        $settings->get_sync_setting_control("record_similar__blocks_sequence")
    ])
    ->add_tab("Дополнительно", [
        Field::make('wa_html', "record_additional")
            ->set_mode("description")
            ->set_html("Дополнительные настройки последовательности блоков на странице, Schema и семантических атрибутов настраиваются в <a href=\"/wp-admin/admin.php?page=wa-profiled-settings.php&target=records\">разделе профилей</a>")
            ->set_style("margin-bottom:5px;"),
    ])
    // ->add_tab("Schema", [
    //     Field::make('wa_html', "record_schema__description")
    //         ->set_mode("description")
    //         ->set_html("Укажите значения Schema.org-атрибутов для соответствующих элементов каждой записи"),
    //     $settings->get_sync_setting_control("record_schema__page_body"),
    //     $settings->get_sync_setting_control("record_schema__theme_header"),
    //     $settings->get_sync_setting_control("record_schema__theme_footer"),
    //     $settings->get_sync_setting_control("record_schema__inner_left_sidebar"),
    //     $settings->get_sync_setting_control("record_schema__inner_right_sidebar"),
    //     $settings->get_sync_setting_control("record_schema__outer_left_sidebar"),
    //     $settings->get_sync_setting_control("record_schema__outer_right_sidebar"),
    //     $settings->get_sync_setting_control("record_schema__article_container"),
    //     $settings->get_sync_setting_control("record_schema__content_section"),
    //     $settings->get_sync_setting_control("record_schema__h1"),
    //     $settings->get_sync_setting_control("record_schema__about_author")
    // ])
    // ->add_tab("Семантика", [
    //     Field::make('wa_html', "record_semantics__description")
    //         ->set_mode("description")
    //         ->set_html("Укажите значения вариантов исполнения для соответствующих элементов каждой записи"),
    //     $settings->get_sync_setting_control("record_semantics__theme_header"),
    //     $settings->get_sync_setting_control("record_semantics__theme_footer"),
    //     $settings->get_sync_setting_control("record_semantics__main_content"),
    //     $settings->get_sync_setting_control("record_semantics__inner_left_sidebar"),
    //     $settings->get_sync_setting_control("record_semantics__inner_right_sidebar"),
    //     $settings->get_sync_setting_control("record_semantics__outer_left_sidebar"),
    //     $settings->get_sync_setting_control("record_semantics__outer_right_sidebar"),
    //     $settings->get_sync_setting_control("record_semantics__page_header"),
    //     $settings->get_sync_setting_control("record_semantics__page_article"),
    //     $settings->get_sync_setting_control("record_semantics__page_headline"),
    //     $settings->get_sync_setting_control("record_semantics__page_headings")
    // ])
    ;





