<?php

    // Блок последовательность блоков

    $this->config["record__blocks_sequence"] = [
        "type" => "sortable_toggle_list",
        "default" => "breadcrumbs:1,main_article_open:1,header_open:1,img:1,header:1,meta_above:1,header_close:1,contents_list:1,content:1,meta_below:1,main_article_close:1,social_buttons:1,author:1,comments:1,footer_open:1,similar_records:1",
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
            'meta_below' => 'Мета-блок нижний',
            'main_article_close' => ['label' => '</article></main>', 'class' => 'wa-sequence-section-fix metaitem'],
            'social_buttons' => 'Социальные кнопки',
            'author' => 'Об авторе',
            'comments' => 'Комментарии',
            'similar_records' => 'Похожие записи'
        ]
    ];

    // Блок мета-данные записи

    $this->init_bool_field_config("record_meta__use_icons", "Мета-иконки",
        "Отображать мета-данные поста в виде набора иконок вместо обычной строки");

    $this->config["record_meta__blocks_sequence"] = [
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

    $this->init_bool_field_config("record__img_outside", "Внешняя миниатюра",
        "Отображать миниатюру поста в самом верху страницы");

    $this->init_bool_field_config("record__date_in_square", "Дата в квадрате",
        "Отображать дату поста в квадрате поверх миниатюры");

    $this->init_bool_field_config("record__prev_next_links", "Следующая-предыдущая записи",
        "Отображать ссылки на предыдущую / следующую записи в конце текущей");

    $this->config["record__author_link_mode"] = [
        "type" => "check_list",
        "default" => "comments:1,meta:1,about:1",
        "title" => "Добавлять ссылку автора",
        "description" => "",
        'choices' => [
            'comments' => 'К имени автора комментария',
            'meta' => 'К имени автора поста',
            'about' => 'В блоке "Об авторе"'
        ]
    ];

    $this->init_text_field_config("record__about_author_label", "Заголовок вместо \"Об авторе\"", "", "Об авторе");

    $this->init_text_field_config("record__similar_records_label", "Заголовок вместо \"Похожие записи\"", "", "Похожие записи");

    // Блок "Комментарии"

    $this->init_bool_field_config("record_comments__save_name", "Разрешить запоминать имя",
        "Разрешать гостям ставить галочку для сохранения введенных данных имени и электронной почты для будущих комментариев");

    $this->init_textarea_field_config("record_comments__save_name_label", "Текст разрешения",
        "Текст, выводимый для пользователя напротив галочки сохранения имени",
        "Сохранить моё имя, email и адрес сайта в этом браузере для последующих моих комментариев");

    $this->init_bool_field_config("record_comments__personal_agreement", "Персональные данные",
        "Требовать у всех гостей ставить галочку обработки персональных данных при отправке комментариев");

    $this->init_textarea_field_config("record_comments__personal_agreement_label", "Текст соглашения",
        "Текст, выводимый для пользователя напротив галочки обработки персональных данных",
        "Даю согласие на обработку персональных данных");

    // Блок "Похожие записи"

    $this->init_bool_field_config("record_similar__date_in_square", "Дата в квадрате",
        "Отображать дату каждой похожей записи в квадрате поверх миниатюры");

    $this->init_bool_field_config("record_similar__img_outside", "Внешняя миниатюра",
        "Отображать миниатюру каждой похожей записи в самом верху блока");

    $this->config["record_similar__blocks_sequence"] = [
        "type" => "sortable_toggle_list",
        "default" => "img:1,header:1,meta:1,content:1,more:1",
        "title" => "Последовательность блоков",
        "description" => "Выберите порядок и укажите состав блоков похожих записей",
        'choices' => [
            'img' => 'Миниатюра',
            'header' => 'Заголовок',
            'meta' => 'Мета-блок',
            'content' => 'Содержимое',
            'more' => 'Читать далее'
        ]
    ];

    // Блок "Schema"

    $this->init_schema_field_config("record_schema__page_body", "page body", "", '{"act":true,"text":"Blog"}');
    $this->init_schema_field_config("record_schema__theme_header", "theme header", "", '{"act":true,"text":"WPHeader"}');
    $this->init_schema_field_config("record_schema__theme_footer", "theme footer", "", '{"act":true,"text":"WPFooter"}');
    $this->init_schema_field_config("record_schema__inner_left_sidebar", "inner left sidebar", "", '{"act":true,"text":"WPSideBar"}');
    $this->init_schema_field_config("record_schema__inner_right_sidebar", "inner right sidebar", "", '{"act":true,"text":"WPSideBar"}');
    $this->init_schema_field_config("record_schema__outer_left_sidebar", "outer left sidebar", "", '{"act":true,"text":"WPSideBar"}');
    $this->init_schema_field_config("record_schema__outer_right_sidebar", "outer right sidebar", "", '{"act":true,"text":"WPSideBar"}');
    $this->init_schema_field_config("record_schema__article_container", "article container", "", '{"act":true,"text":"Article"}');
    $this->init_schema_field_config("record_schema__content_section", "content section", "", '{"act":true,"text":"articleBody"}', "property");
    $this->init_schema_field_config("record_schema__h1", "h1", "", '{"act":true,"text":"headline"}', "property");
    $this->init_schema_field_config("record_schema__about_author", "about author", "", '{"act":true,"text":"Person"}');

    // Блок "Семантика"

$this->init_toggle_list_field_config('record_semantics__theme_header', 'theme header', "(заголовок темы)", ["header", "div"]);
$this->init_toggle_list_field_config('record_semantics__theme_footer', 'theme footer', '(подвал темы)', ['footer', 'div']);
$this->init_toggle_list_field_config('record_semantics__main_content', 'main content', '(главный контейнер содержимого)', ['main', 'div']);
$this->init_toggle_list_field_config('record_semantics__inner_left_sidebar', 'inner left sidebar', '(внутренний левый сайдбар)', ['aside', 'div']);
$this->init_toggle_list_field_config('record_semantics__inner_right_sidebar', 'inner right sidebar', '(внутренний правый сайдбар)', ['aside', 'div']);
$this->init_toggle_list_field_config('record_semantics__outer_left_sidebar', 'outer left sidebar', '(внешний левый сайдбар)', ['aside', 'div']);
$this->init_toggle_list_field_config('record_semantics__outer_right_sidebar', 'outer right sidebar', '(внешний правый сайдбар)', ['aside', 'div']);
$this->init_toggle_list_field_config('record_semantics__page_header', 'post header', '(заголовок статьи)', ['header', 'div']);
$this->init_toggle_list_field_config('record_semantics__page_article', 'post article', '(контейнер статьи)', ['article', 'div']);
$this->init_toggle_list_field_config('record_semantics__page_headline', 'post headline', '(надпись заголовка статьи)', ['h1', 'div']);
$this->init_toggle_list_field_config('record_semantics__page_headings', 'post headings', '(блок навигации статьи)', ['nav', 'div']);

$profiled_defaults = [];
$profiled_defaults["blocks_sequence"] = $this->config["record__blocks_sequence"]["default"];
$schema_defaults = [];
foreach($this->config as $k => $v){
    if(str_starts_with($k, "record_schema__"))
        $schema_defaults[str_replace("record_schema__", "", $k)] = $v["default"];
}  
$profiled_defaults["schema"] = $schema_defaults;
$semantics_defaults = []; 
foreach($this->config as $k => $v){
    if(str_starts_with($k, "record_semantics__"))
        $semantics_defaults[str_replace("record_semantics__", "", $k)] = $v["default"];
}  
$profiled_defaults["semantics"] = $semantics_defaults;

$this->config["record__profiled_settings"] = ["default" => $profiled_defaults];
