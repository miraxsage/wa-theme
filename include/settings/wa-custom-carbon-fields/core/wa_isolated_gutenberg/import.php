<?php

require "iso-gutenberg.php";

function wa_import_isolated_gutenberg(){
    if(isset($GLOBALS['wa_import_isolated_gutenberg_done']))
        return;
    $GLOBALS['wa_import_isolated_gutenberg_done'] = true;
    add_action('init', function() {
        $gutenberg = new IsoEditor_Gutenberg();
        $gutenberg->load();
    });

    add_action( 'admin_enqueue_scripts', function(){
        wp_enqueue_script("wa_iso_gutenberg_script", WA_THEME_URI."include/settings/wa-custom-carbon-fields/core/wa_isolated_gutenberg/build/index.js", ['lodash', 'react', 'wp-api-fetch', 'wp-block-editor', 'wp-block-library', 'wp-blocks', 'wp-components', 'wp-compose', 'wp-data', 'wp-deprecated', 'wp-dom', 'wp-editor', 'wp-element', 'wp-format-library', 'wp-hooks', 'wp-i18n', 'wp-is-shallow-equal', 'wp-keyboard-shortcuts', 'wp-keycodes', 'wp-media-utils', 'wp-plugins', 'wp-preferences', 'wp-primitives', 'wp-private-apis', 'wp-viewport'], '', true);
        wp_enqueue_style("wa_iso_gutenberg_style",  WA_THEME_URI."include/settings/wa-custom-carbon-fields/core/wa_isolated_gutenberg/build/style-index.css");

        $default_settings = [
            'editor' => (new IsoEditor_Gutenberg())->get_editor_settings(),
            'iso' => [
                'blocks' => [
                    // 'allowBlocks' => $this->get_allowed_blocks(),
                ],
                'moreMenu' => false,
                'sidebar' => [
                    'inserter' => false,
                    'inspector' => true,
                ],
                'toolbar' => [
                    'inspector' => true,
                    'navigation' => true,
                ],
                'defaultPreferences' => [
                    'fixedToolbar'    => true,
                    'hasFixedToolbar' => true,
                ],
                'allowEmbeds' => [
                    'youtube',
                    'vimeo',
                    'wordpress',
                    'wordpress-tv',
                    'videopress',
                    'crowdsignal',
                    'imgur',
                ],
            ],
            'editorType' => 'core',
            'allowUrlEmbed' => false,
            'pastePlainText' => false,
            'replaceParagraphCode' => false,
            'patchEmoji' => false,
            'pluginsUrl' => plugins_url( '', __DIR__ ),
            'autocompleter' => true,
        ];


        $profiled_settings_configs = [];
        $settings = WaThemeSettings::get();
        foreach(WaThemeSettings::get()->settings_list() as $setting){
            if(preg_match("/^((page|record|archive)_(schema|semantics|_blocks_sequence))/", $setting) === 1)
                $profiled_settings_configs[$setting] = $settings->get_setting_config($setting);
        }
        $profiled_settings_vars = "const wa_common__profiled_settings_configs = JSON.parse(\"".str_replace('"', '\\"', str_replace('\\', '\\\\', json_encode($profiled_settings_configs)))."\");";
        $profiled_settings_vars .= "const wa_common__profiled_settings_default = JSON.parse(\"".str_replace('"', '\\"', str_replace('\\', '\\\\', WaThemeSettings::get()->get_setting_default("common__profiled_settings")))."\")[0];";

        $editor_translations = load_script_textdomain( "wp-edit-post", "default", "" );
        $editor_translations = <<<JS
( function( domain, translations ) {
	var localeData = translations.locale_data[ domain ] || translations.locale_data.messages;
	localeData[""].domain = domain;
	wp.i18n.setLocaleData( localeData, domain );
} )( "default", {$editor_translations} );
JS;
        wp_add_inline_script( 'wa_iso_gutenberg_script', 'const wa_iso_gutenberg_default_settings = JSON.parse(`'.str_replace('\\', '\\\\', json_encode($default_settings)).'`);'.$profiled_settings_vars.$editor_translations);
    });
}




