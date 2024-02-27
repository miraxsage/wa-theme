<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field\Field;

Container::make("term_meta", "wa_category_records_settings", "WebArchitect настройки отображения")
    ->where('term_taxonomy', '=', 'category')
    ->add_tab("Последовательность", [
        Field::make('wa_active_tab_saver', "category_records_active_tab_saver")
            ->set_container("records"),
        Field::make('wa_html', "record__category_description")
            ->set_mode("category_container_description")
            ->set_html("<div class='title'>Записи</div><div class='description'>
                       WebArchitect настройки отображения для записей с текущей 
                       рубрикой в качестве основной, если для записи не установлены 
                       персональные настройки на странице редактирования записи</div>"),
        $settings->get_personal_setting_control("record__blocks_sequence"),
    ])
    ->add_tab("Schema", [
        Field::make('wa_html', "record_schema__description")
            ->set_mode("description")
            ->set_html("Укажите значения Schema.org-атрибутов для соответствующих элементов каждой записи"),
        $settings->get_personal_setting_control("record_schema__page_body"),
        $settings->get_personal_setting_control("record_schema__theme_header"),
        $settings->get_personal_setting_control("record_schema__theme_footer"),
        $settings->get_personal_setting_control("record_schema__inner_left_sidebar"),
        $settings->get_personal_setting_control("record_schema__inner_right_sidebar"),
        $settings->get_personal_setting_control("record_schema__outer_left_sidebar"),
        $settings->get_personal_setting_control("record_schema__outer_right_sidebar"),
        $settings->get_personal_setting_control("record_schema__article_container"),
        $settings->get_personal_setting_control("record_schema__content_section"),
        $settings->get_personal_setting_control("record_schema__h1"),
        $settings->get_personal_setting_control("record_schema__about_author")
    ])
    ->add_tab("Семантика", [
        Field::make('wa_html', "record_semantics__description")
            ->set_mode("description")
            ->set_html("Укажите значения вариантов исполнения для соответствующих элементов каждой записи"),
        $settings->get_personal_setting_control("record_semantics__theme_header"),
        $settings->get_personal_setting_control("record_semantics__theme_footer"),
        $settings->get_personal_setting_control("record_semantics__main_content"),
        $settings->get_personal_setting_control("record_semantics__inner_left_sidebar"),
        $settings->get_personal_setting_control("record_semantics__inner_right_sidebar"),
        $settings->get_personal_setting_control("record_semantics__outer_left_sidebar"),
        $settings->get_personal_setting_control("record_semantics__outer_right_sidebar"),
        $settings->get_personal_setting_control("record_semantics__page_header"),
        $settings->get_personal_setting_control("record_semantics__page_article"),
        $settings->get_personal_setting_control("record_semantics__page_headline"),
        $settings->get_personal_setting_control("record_semantics__page_headings")
    ]);

Container::make("term_meta", "wa_category_archives_settings", "WebArchitect настройки отображения")
    ->where('term_taxonomy', '=', 'category')
    ->add_tab("Последовательность", [
        Field::make('wa_active_tab_saver', "category_archives_active_tab_saver")
            ->set_container("archives"),
        Field::make('wa_html', "archive__category_description")
            ->set_mode("category_container_description")
            ->set_html("<div class='title'>Архивы</div><div class='description'>
                       WebArchitect настройки отображения для страниц 
                       архивов с текущей рубрикой в качестве основной</div>"),
        $settings->get_personal_setting_control("archive__blocks_sequence"),
    ])
    ->add_tab("Мета", [
        $settings->get_personal_setting_control("archive_meta__use_icons"),
        $settings->get_personal_setting_control("archive_meta__blocks_sequence"),
    ])
    ->add_tab("Общие", [
        $settings->get_personal_setting_control("archive__img_position"),
        $settings->get_personal_setting_control("archive__breadcrumbs"),
        $settings->get_personal_setting_control("archive__date_in_square"),
        $settings->get_personal_setting_control("archive__author_link"),
        $settings->get_personal_setting_control("archive__category_description_position"),
        $settings->get_personal_setting_control("archive__more_label"),
    ])
    ->add_tab("Schema", [
        Field::make('wa_html', "archive_schema__description")
            ->set_mode("description")
            ->set_html("Укажите значения Schema.org-атрибутов для соответствующих элементов архива"),
        $settings->get_personal_setting_control("archive_schema__page_body"),
        $settings->get_personal_setting_control("archive_schema__theme_header"),
        $settings->get_personal_setting_control("archive_schema__theme_footer"),
        $settings->get_personal_setting_control("archive_schema__inner_left_sidebar"),
        $settings->get_personal_setting_control("archive_schema__inner_right_sidebar"),
        $settings->get_personal_setting_control("archive_schema__outer_left_sidebar"),
        $settings->get_personal_setting_control("archive_schema__outer_right_sidebar"),
        $settings->get_personal_setting_control("archive_schema_cell__article_container"),
        $settings->get_personal_setting_control("archive_schema_cell__headline"),
        $settings->get_personal_setting_control("archive_schema_cell__excerpt"),
    ])
    ->add_tab("Семантика", [
        Field::make('wa_html', "archive_semantics__description")
            ->set_mode("description")
            ->set_html("Укажите значения вариантов исполнения для соответствующих элементов каждой записи"),
        $settings->get_personal_setting_control("archive_semantics__theme_header"),
        $settings->get_personal_setting_control("archive_semantics__theme_footer"),
        $settings->get_personal_setting_control("archive_semantics__main_content"),
        $settings->get_personal_setting_control("archive_semantics__inner_left_sidebar"),
        $settings->get_personal_setting_control("archive_semantics__inner_right_sidebar"),
        $settings->get_personal_setting_control("archive_semantics__outer_left_sidebar"),
        $settings->get_personal_setting_control("archive_semantics__outer_right_sidebar"),
        $settings->get_personal_setting_control("archive_semantics__archive_headline"),
        $settings->get_personal_setting_control("archive_semantics_cell__article"),
        $settings->get_personal_setting_control("archive_semantics_cell__header"),
        $settings->get_personal_setting_control("archive_semantics_cell__headline"),
    ]);