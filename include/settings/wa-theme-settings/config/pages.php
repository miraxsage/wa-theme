<?php

// Блок последовательность блоков

$this->config["page__blocks_sequence"] = [
    "type" => "sortable_toggle_list",
    "default" => "breadcrumbs:1,main_article_open:1,header_open:1,img:1,header:1,meta_above:1,header_close:1,contents_list:1,content:1,main_article_close:1,social_buttons:1,comments:1",
    "title" => "Последовательность блоков",
    "description" => "Укажите видимость и порядок следования блоков для каждой записи",
    'choices' => [
        'breadcrumbs' => 'Хлебные крошки',
        'main_article_open' => ['label' => '<main><article>', 'class' => 'wa-sequence-section-fix metaitem'],
        'header_open' => ['label' => '<header>', 'class' => 'wa-sequence-header-fix metaitem'],
        'img' => 'Миниатюра',
        'header' => 'Заголовок',
        'meta_above' => 'Мета-блок',
        'header_close' => ['label' => '</header>', 'class' => 'wa-sequence-header-fix metaitem'],
        'contents_list' => 'Оглавление',
        'content' => ['label' => 'Содержимое', 'class' => 'wa-sequence-mainart-fix metaitem'],
        'main_article_close' => ['label' => '</article></main>', 'class' => 'wa-sequence-section-fix metaitem'],
        'social_buttons' => 'Социальные кнопки',
        'comments' => 'Комментарии'
    ]
];

// Блок мета-данные страницы

$this->init_bool_field_config("page_meta__use_icons", "Мета-иконки",
    "Отображать мета-данные страницы в виде набора иконок вместо обычной строки");

$this->config["page_meta__blocks_sequence"] = [
    "type" => "sortable_toggle_list",
    "default" => "comments:1,created:1,updated:1,views:1,read_time:1",
    "title" => "Последовательность мета-блоков",
    "description" => "Выберите порядок и укажите состав метаданных для каждой страницы",
    'choices' => [
        'comments' => 'Комментариев',
        'created' => 'Опубликовано',
        'updated' => 'Обновлено',
        'views' => 'Просмотров',
        'read_time' => 'На чтение',
    ]
];

// Блок "Общие"

$this->init_bool_field_config("page__img_outside", "Внешняя миниатюра",
    "Отображать миниатюру страницы в самом верху страницы");

$this->init_bool_field_config("page__date_in_square", "Дата в квадрате",
    "Отображать дату страницы в квадрате поверх миниатюры");

$this->config["page__author_link_mode"] = [
    "type" => "check_list",
    "default" => "comments:1,meta:1,about:1",
    "title" => "Добавлять ссылку автора",
    "description" => "",
    'choices' => [
        'comments' => 'К имени автора комментария'
    ]
];

// Блок "Комментарии"

$this->init_bool_field_config("page_comments__save_name", "Разрешить запоминать имя",
    "Разрешать гостям ставить галочку для сохранения введенных данных имени и электронной почты для будущих комментариев");

$this->init_textarea_field_config("page_comments__save_name_label", "Текст разрешения",
    "Текст, выводимый для пользователя напротив галочки сохранения имени",
    "Сохранить моё имя, email и адрес сайта в этом браузере для последующих моих комментариев");

$this->init_bool_field_config("page_comments__personal_agreement", "Персональные данные",
    "Требовать у всех гостей ставить галочку обработки персональных данных при отправке комментариев");

$this->init_textarea_field_config("page_comments__personal_agreement_label", "Текст соглашения",
    "Текст, выводимый для пользователя напротив галочки обработки персональных данных",
    "Даю согласие на обработку персональных данных");

// Блок "Schema"

$this->init_schema_field_config("page_schema__page_body", "page body", "", '{"act":true,"text":"WebPage"}');
$this->init_schema_field_config("page_schema__theme_header", "theme header", "", '{"act":true,"text":"WPHeader"}');
$this->init_schema_field_config("page_schema__theme_footer", "theme footer", "", '{"act":true,"text":"WPFooter"}');
$this->init_schema_field_config("page_schema__inner_left_sidebar", "inner left sidebar", "", '{"act":true,"text":"WPSideBar"}');
$this->init_schema_field_config("page_schema__inner_right_sidebar", "inner right sidebar", "", '{"act":true,"text":"WPSideBar"}');
$this->init_schema_field_config("page_schema__outer_left_sidebar", "outer left sidebar", "", '{"act":true,"text":"WPSideBar"}');
$this->init_schema_field_config("page_schema__outer_right_sidebar", "outer right sidebar", "", '{"act":true,"text":"WPSideBar"}');
$this->init_schema_field_config("page_schema__article_container", "article container", "", '{"act":true,"text":"Article"}');
$this->init_schema_field_config("page_schema__content_section", "content section", "", '{"act":true,"text":"articleBody"}', 'property');
$this->init_schema_field_config("page_schema__h1", "h1", "", '{"act":true,"text":"headline"}', 'property');
$this->init_schema_field_config("page_schema__about_author", "about author", "", '{"act":true,"text":"Person"}');

// Блок "Семантика"

$this->init_toggle_list_field_config('page_semantics__theme_header', 'theme header', "(заголовок темы)", ["header", "div"]);
$this->init_toggle_list_field_config('page_semantics__theme_footer', 'theme footer', '(подвал темы)', ['footer', 'div']);
$this->init_toggle_list_field_config('page_semantics__main_content', 'main content', '(главный контейнер содержимого)', ['main', 'div']);
$this->init_toggle_list_field_config('page_semantics__inner_left_sidebar', 'inner left sidebar', '(внутренний левый сайдбар)', ['aside', 'div']);
$this->init_toggle_list_field_config('page_semantics__inner_right_sidebar', 'inner right sidebar', '(внутренний правый сайдбар)', ['aside', 'div']);
$this->init_toggle_list_field_config('page_semantics__outer_left_sidebar', 'outer left sidebar', '(внешний левый сайдбар)', ['aside', 'div']);
$this->init_toggle_list_field_config('page_semantics__outer_right_sidebar', 'outer right sidebar', '(внешний правый сайдбар)', ['aside', 'div']);
$this->init_toggle_list_field_config('page_semantics__page_article', 'page article', '(контейнер страницы)', ['article', 'div']);
$this->init_toggle_list_field_config('page_semantics__page_header', 'page header', '(заголовок страницы)', ['header', 'div']);
$this->init_toggle_list_field_config('page_semantics__page_headline', 'page headline', '(надпись заголовка страницы)', ['h1', 'div']);
$this->init_toggle_list_field_config('page_semantics__page_headings', 'page headings', '(блок навигации страницы)', ['nav', 'div']);

