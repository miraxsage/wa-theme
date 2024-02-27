<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field\Field;

require_once(WA_THEME_DIR."/include/settings/wa-custom-carbon-fields/autoload.php");

$settings = WaThemeSettings::get();

Container::make("theme_options", "wa_settings", "WebArchitect")
    ->set_icon("data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgdmlld0JveD0iMCAwIDIwIDIwIj4NCjxnPg0KCTxwYXRoIGZpbGw9IiNhN2FhYWQiIGQ9Ik0yLjQ5LDQuMDljLTAuNjEsMS4xOS0xLjIsMi4zNi0xLjgsMy41NEMwLjYzLDcuNTEsMC41OSw3LjQxLDAuNTQsNy4zQzAuMzgsNi45NiwwLjIyLDYuNjEsMC4wNiw2LjI3DQoJCUMwLjA0LDYuMjIsMC4wMyw2LjE0LDAuMDYsNi4wOWMxLTEuOTgsMi0zLjk1LDMuMDEtNS45MkMzLjA3LDAuMTYsMy4wOCwwLjE1LDMuMSwwLjEzQzMuMTIsMC4xNywzLjE0LDAuMiwzLjE2LDAuMjQNCgkJYzEuMzQsMi42NSwyLjY3LDUuMyw0LjAxLDcuOTVjMC4zMSwwLjYxLDAuNjIsMS4yMywwLjkyLDEuODRjMC4wMiwwLjA0LDAuMDIsMC4xMi0wLjAxLDAuMTdjLTEuMTMsMS44My0yLjI3LDMuNjUtMy40MSw1LjQ4DQoJCWMtMC4wMSwwLjAxLTAuMDIsMC4wMi0wLjA0LDAuMDVjLTAuMDgtMC4yMS0wLjE2LTAuNDEtMC4yNC0wLjYxYy0wLjIzLTAuNTgtMC40Ni0xLjE3LTAuNy0xLjc1Yy0wLjA0LTAuMS0wLjA0LTAuMTgsMC4wMi0wLjI3DQoJCWMwLjU5LTAuOTQsMS4xOC0xLjg5LDEuNzgtMi44NGMwLjA0LTAuMDcsMC4wNS0wLjEyLDAuMDEtMC4yQzQuNTIsOC4xLDMuNTQsNi4xNSwyLjU1LDQuMkMyLjU0LDQuMTcsMi41Miw0LjE1LDIuNDksNC4wOXoiLz4NCgk8cGF0aCBmaWxsPSIjYTdhYWFkIiBkPSJNMTYuODMsNy45MUMxNi4xMSw2LjgsMTUuNCw1LjcsMTQuNjgsNC41OGMwLjMyLDAuMDMsMC42MiwwLjA2LDAuOTIsMC4wOWMwLjEzLDAuMDEsMC4yNywwLjA0LDAuNCwwLjA0DQoJCWMwLjIxLTAuMDEsMC4zMywwLjA5LDAuNDQsMC4yNmMxLjE0LDEuNzgsMi4yOSwzLjU2LDMuNDQsNS4zM2MwLjAyLDAuMDQsMC4wNSwwLjA4LDAuMDgsMC4xM2MtMC4wOSwwLTAuMTcsMC0wLjI1LDANCgkJYy0xLjU5LTAuMS0zLjE5LTAuMTktNC43OC0wLjI5Yy0yLTAuMTItNC4wMS0wLjI0LTYuMDEtMC4zNkM4LjgzLDkuNzcsOC43OCw5Ljc0LDguNzMsOS42NkM3Ljc0LDcuNzgsNi43NSw1Ljg5LDUuNzUsNC4wMQ0KCQljLTAuMDEtMC4wMi0wLjAyLTAuMDUtMC4wNS0wLjFjMC4xNywwLjAyLDAuMzEsMC4wNCwwLjQ2LDAuMDdjMC42OSwwLjExLDEuMzgsMC4yMSwyLjA4LDAuMzFjMC4xLDAuMDEsMC4xNSwwLjA1LDAuMTksMC4xNA0KCQljMC41MSwwLjk4LDEuMDMsMS45NSwxLjU0LDIuOTNjMC4wNiwwLjExLDAuMTIsMC4xNiwwLjI0LDAuMTZjMS4wOCwwLjA2LDIuMTcsMC4xMywzLjI1LDAuMTljMS4wNiwwLjA2LDIuMTIsMC4xMywzLjE4LDAuMTkNCgkJQzE2LjcsNy45MSwxNi43Niw3LjkxLDE2LjgzLDcuOTF6Ii8+DQoJPHBhdGggZmlsbD0iI2E3YWFhZCIgZD0iTTE1LjQsMTAuNzdjLTAuNDYsMC41OC0wLjkxLDEuMTQtMS4zNSwxLjdjLTAuMDYsMC4wNy0wLjEyLDAuMTMtMC4xNywwLjIxYy0wLjEsMC4xNy0wLjI0LDAuMi0wLjQzLDAuMg0KCQljLTEuMDctMC4wNS0yLjEzLTAuMDktMy4yLTAuMTNjLTAuMTEsMC0wLjE3LDAuMDItMC4yMywwLjEyYy0xLjE2LDEuNzYtMi4zMiwzLjUyLTMuNDgsNS4yN2MtMC4wNSwwLjA4LTAuMTEsMC4xNy0wLjE4LDAuMjcNCgkJYzEuMzMtMC4wNywyLjYzLTAuMTMsMy45Ni0wLjJjLTAuMDIsMC4wNC0wLjA0LDAuMDctMC4wNiwwLjFjLTAuMjYsMC4zNy0wLjUzLDAuNzMtMC43OSwxLjExYy0wLjA1LDAuMDgtMC4xMSwwLjExLTAuMjEsMC4xMg0KCQljLTEuMTYsMC4wNi0yLjMyLDAuMTItMy40OCwwLjE3Yy0xLjAxLDAuMDUtMi4wMSwwLjEtMy4wMiwwLjE1Yy0wLjA0LDAtMC4wOSwwLTAuMTUsMGMwLjE3LTAuMjUsMC4zMi0wLjQ5LDAuNDgtMC43Mg0KCQljMS44OC0yLjg0LDMuNzUtNS42OCw1LjYzLTguNTJjMC4wNC0wLjA3LDAuMDktMC4xLDAuMTctMC4wOWMyLjE0LDAuMDgsNC4yOCwwLjE2LDYuNDMsMC4yNEMxNS4zNCwxMC43NiwxNS4zNiwxMC43NywxNS40LDEwLjc3eiINCgkJLz4NCjwvZz4NCjwvc3ZnPg==")
    ->set_page_file("wa-settings.php");

add_action( 'admin_menu', function(){
    remove_submenu_page("wa-settings.php", "wa-settings.php");
}, 1000);

Container::make("theme_options", "wa_common_settings", "Общие настройки")
    ->set_page_file("wa-common-settings.php")
    ->set_page_menu_title("Общие")
    ->set_page_parent("wa-settings.php")
    ->add_tab("Хлебные крошки", [
        Field::make('wa_active_tab_saver', "common_active_tab_saver"),
        $settings->get_sync_setting_control("common_breadcrumbs__show_current_page"),
        $settings->get_sync_setting_control("common_breadcrumbs__use_main_page_icon"),
        $settings->get_sync_setting_control("common_breadcrumbs__use_nav"),
    ])
    ->add_tab("Прочее", [
        $settings->get_sync_setting_control("common__pagination_use_nav"),
        $settings->get_sync_setting_control("common__expand_content_titles"),
        $settings->get_sync_setting_control("common__code_at_head_end"),
        $settings->get_sync_setting_control("common__code_at_body_end")
    ]);

Container::make("theme_options", "wa_widgets_settings", "Виджеты")
    ->set_page_file("wa-widgets-settings.php")
    ->set_page_menu_title("Виджеты")
    ->set_page_parent("wa-settings.php")
    ->add_fields([
        $settings->get_sync_setting_control("common__sidebars_configuration"),
    ]);



