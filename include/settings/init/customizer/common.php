<?php
$customizer->add_section(
    'wa_common',
    array('title' => 'Общие', 'panel' => 'webarch')
);

function init_customizer_setting($customizer, $setting, $args){
    $customizer->add_setting($setting, ["default" => WaThemeSettings::get()->get_setting_default($setting)]);
    $customizer->add_control(WaThemeSettings::get()->get_setting_control($setting, $args, $customizer));
}

function init_customizer_container($customizer, $container, $label,  $section, $description = ''){
    $customizer->add_setting($container);
    $container = new WebArch_Container_Custom_Control($customizer, $container,
        array(
            'label' => $label,
            'section' => $section,
            'description' => $description
        ));
    $customizer->add_control($container);
}
function init_customizer_divider($customizer, $divider, $section, $container){
    $customizer->add_setting($divider);
    $control = new WebArch_Divider_Custom_Control($customizer, $divider,
        array(
            'container' => $container,
            'section' => $section
        ));
    $customizer->add_control($control);
}

init_customizer_container($customizer, 'common_breadcrumbs__container', 'Хлебные крошки', 'wa_common');
$cur_args = ['section' => 'wa_common', 'container' => 'common_breadcrumbs__container'];
    init_customizer_setting($customizer, 'common_breadcrumbs__show_current_page', $cur_args);
    init_customizer_setting($customizer, 'common_breadcrumbs__use_main_page_icon', $cur_args);
    init_customizer_setting($customizer, 'common_breadcrumbs__use_nav', $cur_args);
    init_customizer_divider($customizer, 'common_breadcrumbs__divider', $cur_args["section"], $cur_args["container"]);

$cur_args = ['section' => 'wa_common'];
    init_customizer_setting($customizer, 'common__pagination_use_nav', $cur_args);
    init_customizer_setting($customizer, 'common__expand_content_titles', $cur_args);
    init_customizer_setting($customizer, 'common__code_at_head_end', $cur_args);
    init_customizer_setting($customizer, 'common__code_at_body_end', $cur_args);
