<?php
$customizer->add_section('wa_archives',  array('title' => 'Архивы', 'panel' => 'webarch'));

// init_customizer_container($customizer, 'archive_blocks_sequence__container', 'Последовательность блоков', 'wa_archives');
// $cur_args = ['section' => 'wa_archives', 'container' => 'archive_blocks_sequence__container'];
//     init_customizer_setting($customizer, 'archive__blocks_sequence', $cur_args);

init_customizer_container($customizer, 'archive_meta__container', 'Мета-данные записи', 'wa_archives');
$cur_args = ['section' => 'wa_archives', 'container' => 'archive_meta__container'];
    init_customizer_setting($customizer, 'archive_meta__use_icons', $cur_args);
    init_customizer_setting($customizer, 'archive_meta__blocks_sequence', $cur_args);

init_customizer_container($customizer, 'archive_common__container', 'Общие', 'wa_archives');
$cur_args = ['section' => 'wa_archives', 'container' => 'archive_common__container'];
    init_customizer_setting($customizer, 'archive__img_position', $cur_args);
    init_customizer_setting($customizer, 'archive__breadcrumbs', $cur_args);
    init_customizer_setting($customizer, 'archive__date_in_square', $cur_args);
    init_customizer_setting($customizer, 'archive__author_link', $cur_args);
    init_customizer_setting($customizer, 'archive__category_description_position', $cur_args);
    init_customizer_setting($customizer, 'archive__more_label', $cur_args);

init_customizer_container($customizer, 'archive_additional__container', 'Расширенные настройки', 'wa_archives');
$cur_args = ['section' => 'wa_archives', 'container' => 'archive_additional__container'];
    $customizer->add_setting("archive_additional__redirect_descr", ["default" => ""]);
    $customizer->add_control(new WebArch_HTML_Control($customizer, "archive_additional__redirect_descr",
    array_merge(
        $cur_args,
        [
            'content' => <<<CODE
            Следующие настройки для архивов (а также записей и страниц):<br/>
            - последовательности блоков контента<br/>
            - включение \ отключение и заполнение Schema.org<br/> 
            - включение \ отключение html5 тегов (семантические теги)<br/>
            осуществляются на в соответствующей <a href="/wp-admin/admin.php?page=wa-profiled-settings.php&target=archives">вкладке</a> раздела «Виджеты и профили»
    CODE
        ]))
    );

// init_customizer_container($customizer, 'archive_schema__container', 'Schema.org', 'wa_archives',
//     'Укажите значения Schema.org-атрибутов для соответствующих элементов каждой записи');
// $cur_args = ['section' => 'wa_archives', 'container' => 'archive_schema__container'];
//     init_customizer_setting($customizer, 'archive_schema__page_body', $cur_args);
//     init_customizer_setting($customizer, 'archive_schema__theme_header', $cur_args);
//     init_customizer_setting($customizer, 'archive_schema__theme_footer', $cur_args);
//     init_customizer_setting($customizer, 'archive_schema__inner_left_sidebar', $cur_args);
//     init_customizer_setting($customizer, 'archive_schema__inner_right_sidebar', $cur_args);
//     init_customizer_setting($customizer, 'archive_schema__outer_left_sidebar', $cur_args);
//     init_customizer_setting($customizer, 'archive_schema__outer_right_sidebar', $cur_args);
//     init_customizer_setting($customizer, 'archive_schema_cell__article_container', $cur_args);
//     init_customizer_setting($customizer, 'archive_schema_cell__headline', $cur_args);
//     init_customizer_setting($customizer, 'archive_schema_cell__excerpt', $cur_args);

// init_customizer_container($customizer, 'archive_semantics__container', 'Семантика', 'wa_archives',
//     'Укажите значения вариантов исполнения для соответствующих элементов каждой записи');
// $cur_args = ['section' => 'wa_archives', 'container' => 'archive_semantics__container'];
//     init_customizer_setting($customizer, 'archive_semantics__theme_header', $cur_args);
//     init_customizer_setting($customizer, 'archive_semantics__theme_footer', $cur_args);
//     init_customizer_setting($customizer, 'archive_semantics__main_content', $cur_args);
//     init_customizer_setting($customizer, 'archive_semantics__inner_left_sidebar', $cur_args);
//     init_customizer_setting($customizer, 'archive_semantics__inner_right_sidebar', $cur_args);
//     init_customizer_setting($customizer, 'archive_semantics__outer_left_sidebar', $cur_args);
//     init_customizer_setting($customizer, 'archive_semantics__outer_right_sidebar', $cur_args);
//     init_customizer_setting($customizer, 'archive_semantics__archive_headline', $cur_args);
//     init_customizer_setting($customizer, 'archive_semantics_cell__article', $cur_args);
//     init_customizer_setting($customizer, 'archive_semantics_cell__header', $cur_args);
//     init_customizer_setting($customizer, 'archive_semantics_cell__headline', $cur_args);

