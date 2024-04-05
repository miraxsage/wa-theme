<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

require_once(WA_THEME_DIR."/include/settings/wa-custom-carbon-fields/autoload.php");


Container::make("post_meta", "wa_record_settings", "WebArchitect настройки отображения")
    ->where('post_type', '!=', 'page')
    ->add_tab("Последовательность", [
        $settings->get_personal_setting_control("record__blocks_sequence"),
    ])
    //После введения профильных настроек непосредственно на странице поста / страницы 
    //можно настроить только последовательность блоков, которая переопределяет все остальные настройки
    // ->add_tab("Schema", [
    //     Field::make('wa_html', "record_schema__description")
    //         ->set_mode("description")
    //         ->set_html("Укажите значения Schema.org-атрибутов для соответствующих элементов каждой записи"),
    //     $settings->get_personal_setting_control("record_schema__page_body"),
    //     $settings->get_personal_setting_control("record_schema__theme_header"),
    //     $settings->get_personal_setting_control("record_schema__theme_footer"),
    //     $settings->get_personal_setting_control("record_schema__inner_left_sidebar"),
    //     $settings->get_personal_setting_control("record_schema__inner_right_sidebar"),
    //     $settings->get_personal_setting_control("record_schema__outer_left_sidebar"),
    //     $settings->get_personal_setting_control("record_schema__outer_right_sidebar"),
    //     $settings->get_personal_setting_control("record_schema__article_container"),
    //     $settings->get_personal_setting_control("record_schema__content_section"),
    //     $settings->get_personal_setting_control("record_schema__h1"),
    //     $settings->get_personal_setting_control("record_schema__about_author")
    // ])
    // ->add_tab("Семантика", [
    //     Field::make('wa_html', "record_semantics__description")
    //         ->set_mode("description")
    //         ->set_html("Укажите значения вариантов исполнения для соответствующих элементов каждой записи"),
    //     $settings->get_personal_setting_control("record_semantics__theme_header"),
    //     $settings->get_personal_setting_control("record_semantics__theme_footer"),
    //     $settings->get_personal_setting_control("record_semantics__main_content"),
    //     $settings->get_personal_setting_control("record_semantics__inner_left_sidebar"),
    //     $settings->get_personal_setting_control("record_semantics__inner_right_sidebar"),
    //     $settings->get_personal_setting_control("record_semantics__outer_left_sidebar"),
    //     $settings->get_personal_setting_control("record_semantics__outer_right_sidebar"),
    //     $settings->get_personal_setting_control("record_semantics__page_header"),
    //     $settings->get_personal_setting_control("record_semantics__page_article"),
    //     $settings->get_personal_setting_control("record_semantics__page_headline"),
    //     $settings->get_personal_setting_control("record_semantics__page_headings")
    // ])
    ;

/*
function record_semantics($name, $title, $descr, $keys){
    $buttons = [];
    $checked = false;
    foreach ($keys as $key){
        $buttons[$key] = ["text" => $key, "act" => !$checked];
        $checked = true;
    }
    return Field::make( 'wa_toggle_button_list', $name, $title )
        ->set_mode("radio")
        ->set_description($descr)
        ->set_buttons($buttons);
}

Container::make("post_meta", "wa_record_settings", "WebArchitect настройки отображения")
    ->add_tab("Последовательность", array(
        Field::make('wa_advanced_pill_list', 'record_blocks_sequence', 'Порядок блоков')
            ->set_description("Укажите видимость и порядок следования блоков для текущей записи")
            ->set_choices(array(
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
            ))
    ))
    ->add_tab("Schema.org", array(
        Field::make( 'wa_html', 'schema_record_description' )
            ->set_html('Укажите значения Schema.org-атрибутов для соответствующих элементов каждой записи'),
        Field::make( 'wa_schema_info', 'schema_record_page_body', 'page body' )
            ->set_default_text("WebPage"),
        Field::make( 'wa_schema_info', 'schema_record_theme_header', 'theme header' )
            ->set_default_text("WPHeader"),
        Field::make( 'wa_schema_info', 'schema_record_theme_footer', 'theme footer' )
            ->set_default_text("WPFooter"),
        Field::make( 'wa_schema_info', 'schema_record_inner_left_sidebar', 'inner left sidebar' )
            ->set_default_text("WPSideBar"),
        Field::make( 'wa_schema_info', 'schema_record_inner_right_sidebar', 'inner right sidebar' )
            ->set_default_text("WPSideBar"),
        Field::make( 'wa_schema_info', 'schema_record_outer_left_sidebar', 'outer left sidebar' )
            ->set_default_text("WPSideBar"),
        Field::make( 'wa_schema_info', 'schema_record_outer_right_sidebar', 'outer right sidebar' )
            ->set_default_text("WPSideBar"),
        Field::make( 'wa_schema_info', 'schema_record_article_container', 'article container' )
            ->set_default_text("Article"),
        Field::make( 'wa_schema_info', 'schema_record_content_section', 'content section' )
            ->set_default_text("articleBody"),
        Field::make( 'wa_schema_info', 'schema_record_h1', 'h1' )
            ->set_default_text("headline"),
        Field::make( 'wa_schema_info', 'schema_record_about_author', 'about author' )
            ->set_default_text("Person"),
    ))
    ->add_tab("Семантика", array(
        Field::make( 'wa_html', 'record_semantics_description' )
            ->set_html('Укажите значения вариантов исполнения для соответствующих элементов каждой записи'),
        record_semantics('record_semantics_theme_header', 'theme header', '(заголовок темы)', ['header', 'div']),
        record_semantics('record_semantics_theme_footer', 'theme footer', '(подвал темы)', ['footer', 'div']),
        record_semantics('record_semantics_main_content', 'main content', '(главный контейнер содержимого)', ['main', 'div']),
        record_semantics('record_semantics_inner_left_sidebar', 'inner left sidebar', '(внутренний левый сайдбар)', ['aside', 'div']),
        record_semantics('record_semantics_inner_right_sidebar', 'inner roght sidebar', '(внутренний правый сайдбар)', ['aside', 'div']),
        record_semantics('record_semantics_outer_left_sidebar', 'outer left sidebar', '(внешний левый сайдбар)', ['aside', 'div']),
        record_semantics('record_semantics_outer_right_sidebar', 'outer roght sidebar', '(внешний правый сайдбар)', ['aside', 'div']),
        record_semantics('record_semantics_page_article', 'post article', '(контейнер статьи)', ['article', 'div']),
        record_semantics('record_semantics_page_headline', 'post headline', '(надпись заголовка статьи)', ['h1', 'div']),
        record_semantics('record_semantics_page_headings', 'post headings', '(блок навигации статьи)', ['nav', 'div'])
    ));
*/
