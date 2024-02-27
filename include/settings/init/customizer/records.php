<?php
$customizer->add_section('wa_records',  array('title' => 'Записи', 'panel' => 'webarch'));

init_customizer_container($customizer, 'record_blocks_sequence__container', 'Последовательность блоков', 'wa_records');
$cur_args = ['section' => 'wa_records', 'container' => 'record_blocks_sequence__container'];
    init_customizer_setting($customizer, 'record__blocks_sequence', $cur_args);

init_customizer_container($customizer, 'record_meta__container', 'Мета-данные записи', 'wa_records');
$cur_args = ['section' => 'wa_records', 'container' => 'record_meta__container'];
    init_customizer_setting($customizer, 'record_meta__use_icons', $cur_args);
    init_customizer_setting($customizer, 'record_meta__blocks_sequence', $cur_args);

init_customizer_container($customizer, 'record_common__container', 'Общие', 'wa_records');
$cur_args = ['section' => 'wa_records', 'container' => 'record_common__container'];
    init_customizer_setting($customizer, 'record__img_outside', $cur_args);
    init_customizer_setting($customizer, 'record__date_in_square', $cur_args);
    init_customizer_setting($customizer, 'record__prev_next_links', $cur_args);
    init_customizer_setting($customizer, 'record__author_link_mode', $cur_args);
    init_customizer_setting($customizer, 'record__about_author_label', $cur_args);
    init_customizer_setting($customizer, 'record__similar_records_label', $cur_args);

init_customizer_container($customizer, 'record_comments__container', 'Комментарии', 'wa_records');
$cur_args = ['section' => 'wa_records', 'container' => 'record_comments__container'];
    init_customizer_setting($customizer, 'record_comments__save_name', $cur_args);
    init_customizer_setting($customizer, 'record_comments__save_name_label', $cur_args);
    init_customizer_setting($customizer, 'record_comments__personal_agreement', $cur_args);
    init_customizer_setting($customizer, 'record_comments__personal_agreement_label', $cur_args);

init_customizer_container($customizer, 'record_similar_records__container', 'Похожие записи', 'wa_records');
$cur_args = ['section' => 'wa_records', 'container' => 'record_similar_records__container'];
    init_customizer_setting($customizer, 'record_similar__date_in_square', $cur_args);
    init_customizer_setting($customizer, 'record_similar__img_outside', $cur_args);
    init_customizer_setting($customizer, 'record_similar__blocks_sequence', $cur_args);

init_customizer_container($customizer, 'record_schema__container', 'Schema.org', 'wa_records',
    'Укажите значения Schema.org-атрибутов для соответствующих элементов каждой записи');
$cur_args = ['section' => 'wa_records', 'container' => 'record_schema__container'];
    init_customizer_setting($customizer, 'record_schema__page_body', $cur_args);
    init_customizer_setting($customizer, 'record_schema__theme_footer', $cur_args);
    init_customizer_setting($customizer, 'record_schema__inner_left_sidebar', $cur_args);
    init_customizer_setting($customizer, 'record_schema__inner_right_sidebar', $cur_args);
    init_customizer_setting($customizer, 'record_schema__outer_left_sidebar', $cur_args);
    init_customizer_setting($customizer, 'record_schema__outer_right_sidebar', $cur_args);
    init_customizer_setting($customizer, 'record_schema__article_container', $cur_args);
    init_customizer_setting($customizer, 'record_schema__content_section', $cur_args);
    init_customizer_setting($customizer, 'record_schema__h1', $cur_args);
    init_customizer_setting($customizer, 'record_schema__theme_header', $cur_args);
    init_customizer_setting($customizer, 'record_schema__about_author', $cur_args);

init_customizer_container($customizer, 'record_semantics__container', 'Семантика', 'wa_records',
    'Укажите значения вариантов исполнения для соответствующих элементов каждой записи');
$cur_args = ['section' => 'wa_records', 'container' => 'record_semantics__container'];
    init_customizer_setting($customizer, 'record_semantics__theme_header', $cur_args);
    init_customizer_setting($customizer, 'record_semantics__theme_footer', $cur_args);
    init_customizer_setting($customizer, 'record_semantics__main_content', $cur_args);
    init_customizer_setting($customizer, 'record_semantics__inner_left_sidebar', $cur_args);
    init_customizer_setting($customizer, 'record_semantics__inner_right_sidebar', $cur_args);
    init_customizer_setting($customizer, 'record_semantics__outer_left_sidebar', $cur_args);
    init_customizer_setting($customizer, 'record_semantics__outer_right_sidebar', $cur_args);
    init_customizer_setting($customizer, 'record_semantics__page_article', $cur_args);
    init_customizer_setting($customizer, 'record_semantics__page_header', $cur_args);
    init_customizer_setting($customizer, 'record_semantics__page_headline', $cur_args);
    init_customizer_setting($customizer, 'record_semantics__page_headings', $cur_args);
