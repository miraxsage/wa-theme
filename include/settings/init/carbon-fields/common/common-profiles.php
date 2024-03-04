<?php
use Carbon_Fields\Container;

Container::make("theme_options", "wa_profiled_settings", "Виджеты и дополнительные настройки")
    ->set_page_file("wa-profiled-settings.php")
    ->set_page_menu_title("Виджеты / Профили")
    ->set_page_parent("wa-settings.php")
    ->add_fields([
        $settings->get_sync_setting_control("common__profiled_settings"),
    ]);