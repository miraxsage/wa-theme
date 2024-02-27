<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field\Field;

Container::make("theme_options", "wa_archives_settings", "Настройки архивов")
    ->set_page_file("wa-archives-settings.php")
    ->set_page_menu_title("Архивы")
    ->set_page_parent("wa-settings.php")
    ->add_tab("Последовательность", [
        Field::make('wa_active_tab_saver', "archives_active_tab_saver"),
        $settings->get_sync_setting_control("archive__blocks_sequence"),
    ])
    ->add_tab("Мета", [
        $settings->get_sync_setting_control("archive_meta__use_icons"),
        $settings->get_sync_setting_control("archive_meta__blocks_sequence"),
    ])
    ->add_tab("Общие", [
        $settings->get_sync_setting_control("archive__img_position"),
        $settings->get_sync_setting_control("archive__breadcrumbs"),
        $settings->get_sync_setting_control("archive__date_in_square"),
        $settings->get_sync_setting_control("archive__author_link"),
        $settings->get_sync_setting_control("archive__category_description_position"),
        $settings->get_sync_setting_control("archive__more_label"),
    ])
    ->add_tab("Schema", [
        Field::make('wa_html', "archive_schema__description")
            ->set_mode("description")
            ->set_html("Укажите значения Schema.org-атрибутов для соответствующих элементов архива"),
        $settings->get_sync_setting_control("archive_schema__page_body"),
        $settings->get_sync_setting_control("archive_schema__theme_header"),
        $settings->get_sync_setting_control("archive_schema__theme_footer"),
        $settings->get_sync_setting_control("archive_schema__inner_left_sidebar"),
        $settings->get_sync_setting_control("archive_schema__inner_right_sidebar"),
        $settings->get_sync_setting_control("archive_schema__outer_left_sidebar"),
        $settings->get_sync_setting_control("archive_schema__outer_right_sidebar"),
        $settings->get_sync_setting_control("archive_schema_cell__article_container"),
        $settings->get_sync_setting_control("archive_schema_cell__headline"),
        $settings->get_sync_setting_control("archive_schema_cell__excerpt"),
    ])
    ->add_tab("Семантика", [
        Field::make('wa_html', "archive_semantics__description")
            ->set_mode("description")
            ->set_html("Укажите значения вариантов исполнения для соответствующих элементов каждой записи"),
        $settings->get_sync_setting_control("archive_semantics__theme_header"),
        $settings->get_sync_setting_control("archive_semantics__theme_footer"),
        $settings->get_sync_setting_control("archive_semantics__main_content"),
        $settings->get_sync_setting_control("archive_semantics__inner_left_sidebar"),
        $settings->get_sync_setting_control("archive_semantics__inner_right_sidebar"),
        $settings->get_sync_setting_control("archive_semantics__outer_left_sidebar"),
        $settings->get_sync_setting_control("archive_semantics__outer_right_sidebar"),
        $settings->get_sync_setting_control("archive_semantics__archive_headline"),
        $settings->get_sync_setting_control("archive_semantics_cell__article"),
        $settings->get_sync_setting_control("archive_semantics_cell__header"),
        $settings->get_sync_setting_control("archive_semantics_cell__headline"),
    ]);





