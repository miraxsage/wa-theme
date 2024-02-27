<?php
$customizer->add_section('wa_pages',  array('title' => 'Страницы', 'panel' => 'webarch'));

init_customizer_container($customizer, 'page_blocks_sequence__container', 'Последовательность блоков', 'wa_pages');
$cur_args = ['section' => 'wa_pages', 'container' => 'page_blocks_sequence__container'];
    init_customizer_setting($customizer, 'page__blocks_sequence', $cur_args);

init_customizer_container($customizer, 'page_meta__container', 'Мета-данные записи', 'wa_pages');
$cur_args = ['section' => 'wa_pages', 'container' => 'page_meta__container'];
    init_customizer_setting($customizer, 'page_meta__use_icons', $cur_args);
    init_customizer_setting($customizer, 'page_meta__blocks_sequence', $cur_args);

init_customizer_container($customizer, 'page_common__container', 'Общие', 'wa_pages');
$cur_args = ['section' => 'wa_pages', 'container' => 'page_common__container'];
    init_customizer_setting($customizer, 'page__img_outside', $cur_args);
    init_customizer_setting($customizer, 'page__date_in_square', $cur_args);
    init_customizer_setting($customizer, 'page__author_link_mode', $cur_args);

init_customizer_container($customizer, 'page_comments__container', 'Комментарии', 'wa_pages');
$cur_args = ['section' => 'wa_pages', 'container' => 'page_comments__container'];
    init_customizer_setting($customizer, 'page_comments__save_name', $cur_args);
    init_customizer_setting($customizer, 'page_comments__save_name_label', $cur_args);
    init_customizer_setting($customizer, 'page_comments__personal_agreement', $cur_args);
    init_customizer_setting($customizer, 'page_comments__personal_agreement_label', $cur_args);

init_customizer_container($customizer, 'page_schema__container', 'Schema.org', 'wa_pages',
    'Укажите значения Schema.org-атрибутов для соответствующих элементов каждой записи');
$cur_args = ['section' => 'wa_pages', 'container' => 'page_schema__container'];
    init_customizer_setting($customizer, 'page_schema__page_body', $cur_args);
    init_customizer_setting($customizer, 'page_schema__theme_footer', $cur_args);
    init_customizer_setting($customizer, 'page_schema__inner_left_sidebar', $cur_args);
    init_customizer_setting($customizer, 'page_schema__inner_right_sidebar', $cur_args);
    init_customizer_setting($customizer, 'page_schema__outer_left_sidebar', $cur_args);
    init_customizer_setting($customizer, 'page_schema__outer_right_sidebar', $cur_args);
    init_customizer_setting($customizer, 'page_schema__article_container', $cur_args);
    init_customizer_setting($customizer, 'page_schema__content_section', $cur_args);
    init_customizer_setting($customizer, 'page_schema__h1', $cur_args);
    init_customizer_setting($customizer, 'page_schema__theme_header', $cur_args);

init_customizer_container($customizer, 'page_semantics__container', 'Семантика', 'wa_pages',
    'Укажите значения вариантов исполнения для соответствующих элементов каждой записи');
$cur_args = ['section' => 'wa_pages', 'container' => 'page_semantics__container'];
    init_customizer_setting($customizer, 'page_semantics__theme_header', $cur_args);
    init_customizer_setting($customizer, 'page_semantics__theme_footer', $cur_args);
    init_customizer_setting($customizer, 'page_semantics__main_content', $cur_args);
    init_customizer_setting($customizer, 'page_semantics__inner_left_sidebar', $cur_args);
    init_customizer_setting($customizer, 'page_semantics__inner_right_sidebar', $cur_args);
    init_customizer_setting($customizer, 'page_semantics__outer_left_sidebar', $cur_args);
    init_customizer_setting($customizer, 'page_semantics__outer_right_sidebar', $cur_args);
    init_customizer_setting($customizer, 'page_semantics__page_article', $cur_args);
    init_customizer_setting($customizer, 'page_semantics__page_header', $cur_args);
    init_customizer_setting($customizer, 'page_semantics__page_headline', $cur_args);
    init_customizer_setting($customizer, 'page_semantics__page_headings', $cur_args);

