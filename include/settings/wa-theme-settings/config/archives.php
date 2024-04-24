<?php

// Блок последовательность блоков

$this->config["archive__blocks_sequence"] = [
    "type" => "sortable_toggle_list",
    "default" => '{"sequence": "article_open:1,img:1,header_open:1,header:1,meta_above:1,header_close:1,excerpt:1,more:1,article_close:1,block_1:0,block_2:0,block_3:0", "blocks": ["","",""]}',
    "title" => "Последовательность блоков",
    "description" => "Укажите видимость и порядок следования блоков для каждой записи на странице архива",
    'choices' => [
        'article_open' => ['label' => '<article>', 'class' => 'wa-sequence-section-fix metaitem'],
        'img' => 'Миниатюра',
        'header_open' => ['label' => '<header>', 'class' => 'wa-sequence-header-fix metaitem'],
        'header' => 'Заголовок',
        'meta_above' => 'Мета-блок',
        'header_close' => ['label' => '</header>', 'class' => 'wa-sequence-header-fix metaitem'],
        'excerpt' => 'Краткое содержание',
        'more' => 'Читать далее',
        'article_close' => ['label' => '</article>', 'class' => 'wa-sequence-section-fix metaitem'],
    ]
];

// Блок мета-данные записи

$this->init_bool_field_config("archive_meta__use_icons", "Мета-иконки",
    "Отображать мета-данные каждой записи в виде набора иконок вместо обычной строки");

$this->config["archive_meta__blocks_sequence"] = [
    "type" => "sortable_toggle_list",
    "default" => "comments:1,categories:1,marks:1,author:1,created:1,updated:1,views:1,read_time:1",
    "title" => "Последовательность мета-блоков",
    "description" => "Выберите порядок и укажите состав метаданных для каждой записи",
    'choices' => [
        'comments' => 'Комментариев',
        'categories' => 'Категории',
        'marks' => 'Метки',
        'author' => 'Автор',
        'created' => 'Опубликовано',
        'updated' => 'Обновлено',
        'views' => 'Просмотров',
        'read_time' => 'На чтение',
    ]
];

// Блок "Общие"

$this->config["archive__img_position"] = [
    "type" => "toggle_list",
    "default" => "outside:0,above:1,aside:0",
    "title" => "Положение миниатюры",
    "description" => "",
    'choices' => [
        'outside' => 'Снаружи',
        'above' => 'Внутри',
        'aside' => 'Сбоку',
    ]
];

$this->init_bool_field_config("archive__breadcrumbs", "Хлебные крошки",
    "Отображать хлебные крошки в начале страницы");

$this->init_bool_field_config("archive__date_in_square", "Дата в квадрате",
    "Отображать дату каждой записи в квадрате поверх миниатюры");

$this->init_bool_field_config("archive__author_link", "Ссылка на автора",
    "Добавлять ссылку к имени автора в метаданных поста");

$this->config["archive__category_description_position"] = [
    "type" => "toggle_list",
    "default" => "above:1,below:0",
    "title" => "Положение описания категории",
    "description" => "",
    'choices' => [
        'above' => 'Сверху',
        'below' => 'Снизу',
    ]
];

$this->init_text_field_config("archive__more_label", "Заголовок вместо \"Читать далее\"", "", "Читать далее");

// Блок "Schema"

$this->init_schema_field_config("archive_schema__page_body", "page body", "", '{"act":true,"text":"Blog"}');
$this->init_schema_field_config("archive_schema__theme_header", "theme header", "", '{"act":true,"text":"WPHeader"}');
$this->init_schema_field_config("archive_schema__theme_footer", "theme footer", "", '{"act":true,"text":"WPFooter"}');
$this->init_schema_field_config("archive_schema__inner_left_sidebar", "inner left sidebar", "", '{"act":true,"text":"WPSideBar"}');
$this->init_schema_field_config("archive_schema__inner_right_sidebar", "inner right sidebar", "", '{"act":true,"text":"WPSideBar"}');
$this->init_schema_field_config("archive_schema__outer_left_sidebar", "outer left sidebar", "", '{"act":true,"text":"WPSideBar"}');
$this->init_schema_field_config("archive_schema__outer_right_sidebar", "outer right sidebar", "", '{"act":true,"text":"WPSideBar"}');

$this->init_schema_field_config("archive_schema_cell__article_container", "article container", "", '{"act":true,"text":"BlogPosting"}');
$this->init_schema_field_config("archive_schema_cell__headline", "headline", "", '{"act":true,"text":"headline"}', 'property');
$this->init_schema_field_config("archive_schema_cell__excerpt", "excerpt", "", '{"act":true,"text":"articleBody"}', 'property');

// Блок "Семантика"

$this->init_toggle_list_field_config('archive_semantics__theme_header', 'theme header', "(заголовок темы)", ["header", "div"]);
$this->init_toggle_list_field_config('archive_semantics__theme_footer', 'theme footer', '(подвал темы)', ['footer', 'div']);
$this->init_toggle_list_field_config('archive_semantics__main_content', 'main content', '(главный контейнер содержимого)', ['main', 'div']);
$this->init_toggle_list_field_config('archive_semantics__inner_left_sidebar', 'inner left sidebar', '(внутренний левый сайдбар)', ['aside', 'div']);
$this->init_toggle_list_field_config('archive_semantics__inner_right_sidebar', 'inner right sidebar', '(внутренний правый сайдбар)', ['aside', 'div']);
$this->init_toggle_list_field_config('archive_semantics__outer_left_sidebar', 'outer left sidebar', '(внешний левый сайдбар)', ['aside', 'div']);
$this->init_toggle_list_field_config('archive_semantics__outer_right_sidebar', 'outer right sidebar', '(внешний правый сайдбар)', ['aside', 'div']);
$this->init_toggle_list_field_config('archive_semantics__archive_headline', 'h1', '(заголовок архива)', ['h1', 'div']);
$this->init_toggle_list_field_config('archive_semantics_cell__article', 'call article', '(контейнер каждой статьи)', ['article', 'div']);
$this->init_toggle_list_field_config('archive_semantics_cell__header', 'cell header', '(заголовок каждой статьи)', ['header', 'div']);
$this->init_toggle_list_field_config('archive_semantics_cell__headline', 'cell headline', '(текст заголовка каждой статьи)', ['h2', 'div']);

$profiled_defaults = [];
$profiled_defaults["blocks_sequence"] = $this->config["archive__blocks_sequence"]["default"];
$schema_defaults = [];
foreach($this->config as $k => $v){
    if(str_starts_with($k, "archive_schema__"))
        $schema_defaults[str_replace("archive_schema__", "", $k)] = $v["default"];
}  
$profiled_defaults["schema"] = $schema_defaults;
$semantics_defaults = []; 
foreach($this->config as $k => $v){
    if(str_starts_with($k, "archive_semantics__"))
        $semantics_defaults[str_replace("archive_semantics__", "", $k)] = $v["default"];
}  
$profiled_defaults["semantics"] = $semantics_defaults;

$this->config["archive__profiled_settings"] = ["default" => $profiled_defaults];
