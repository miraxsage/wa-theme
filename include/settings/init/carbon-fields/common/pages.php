<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field\Field;

Container::make("theme_options", "wa_pages_settings", "Настройки страниц")
    ->set_page_file("wa-pages-settings.php")
    ->set_page_menu_title("Страницы")
    ->set_page_parent("wa-settings.php")
    ->add_tab("Последовательность", [
        Field::make('wa_active_tab_saver', "pages_active_tab_saver"),
        $settings->get_sync_setting_control("page__blocks_sequence"),
    ])
    ->add_tab("Мета", [
        $settings->get_sync_setting_control("page_meta__use_icons"),
        $settings->get_sync_setting_control("page_meta__blocks_sequence"),
    ])
    ->add_tab("Общие", [
        $settings->get_sync_setting_control("page__img_outside"),
        $settings->get_sync_setting_control("page__date_in_square"),
        $settings->get_sync_setting_control("page__author_link_mode"),
    ])
    ->add_tab("Комментарии", [
        $settings->get_sync_setting_control("page_comments__save_name"),
        $settings->get_sync_setting_control("page_comments__save_name_label"),
        $settings->get_sync_setting_control("page_comments__personal_agreement"),
        $settings->get_sync_setting_control("page_comments__personal_agreement_label")
    ])
    ->add_tab("Schema", [
        Field::make('wa_html', "page_schema__description")
            ->set_mode("description")
            ->set_html("Укажите значения Schema.org-атрибутов для соответствующих элементов каждой записи"),
        $settings->get_sync_setting_control("page_schema__page_body"),
        $settings->get_sync_setting_control("page_schema__theme_header"),
        $settings->get_sync_setting_control("page_schema__theme_footer"),
        $settings->get_sync_setting_control("page_schema__inner_left_sidebar"),
        $settings->get_sync_setting_control("page_schema__inner_right_sidebar"),
        $settings->get_sync_setting_control("page_schema__outer_left_sidebar"),
        $settings->get_sync_setting_control("page_schema__outer_right_sidebar"),
        $settings->get_sync_setting_control("page_schema__article_container"),
        $settings->get_sync_setting_control("page_schema__content_section"),
        $settings->get_sync_setting_control("page_schema__h1"),
    ])
    ->add_tab("Семантика", [
        Field::make('wa_html', "page_semantics__description")
            ->set_mode("description")
            ->set_html("Укажите значения вариантов исполнения для соответствующих элементов каждой записи"),
        $settings->get_sync_setting_control("page_semantics__theme_header"),
        $settings->get_sync_setting_control("page_semantics__theme_footer"),
        $settings->get_sync_setting_control("page_semantics__main_content"),
        $settings->get_sync_setting_control("page_semantics__inner_left_sidebar"),
        $settings->get_sync_setting_control("page_semantics__inner_right_sidebar"),
        $settings->get_sync_setting_control("page_semantics__outer_left_sidebar"),
        $settings->get_sync_setting_control("page_semantics__outer_right_sidebar"),
        $settings->get_sync_setting_control("page_semantics__page_header"),
        $settings->get_sync_setting_control("page_semantics__page_article"),
        $settings->get_sync_setting_control("page_semantics__page_headline"),
        $settings->get_sync_setting_control("page_semantics__page_headings")
    ]);





