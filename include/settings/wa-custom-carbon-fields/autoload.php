<?php

use Carbon_Fields\Carbon_Fields;
use Custom_Carbon_Fields\Wa_Advanced_Pill_List_Field;
use Custom_Carbon_Fields\Wa_Toggle_Field;
use Custom_Carbon_Fields\Wa_Schema_Info_Field;
use Custom_Carbon_Fields\Wa_Toggle_Button_List_Field;
use Custom_Carbon_Fields\Wa_Html_Field;
use Custom_Carbon_Fields\Wa_Text_Field;
use Custom_Carbon_Fields\Wa_Active_Tab_Saver_Field;
use Custom_Carbon_Fields\Wa_Profiled_Settings_Field;
use Custom_Carbon_Fields\Wa_Blocks_Sequence_Field;


define( 'Wa_Custom_Carbon_Fields\\DIR', __DIR__ );
if(!defined('CF_SCRIPT_DEBUG'))
    define('CF_SCRIPT_DEBUG', true);

require_once("core/wa_custom_carbon.php");
require_once("core/wa_active_tab_saver.php");
require_once("core/wa_html.php");
require_once("core/wa_text.php");
require_once("core/wa_advanced_pill_list.php");
require_once("core/wa_toggle.php");
require_once("core/wa_schema_info.php");
require_once("core/wa_toggle_button_list.php");
require_once("core/wa_profiled_settings.php");
require_once("core/wa_blocks_sequence.php");

Carbon_Fields::extend( Wa_Active_Tab_Saver_Field::class, function( $container ) {
    return new Wa_Active_Tab_Saver_Field( $container['arguments']['type'], $container['arguments']['name'], $container['arguments']['label'] );
});
Carbon_Fields::extend( Wa_Html_Field::class, function( $container ) {
    return new Wa_Html_Field( $container['arguments']['type'], $container['arguments']['name'], $container['arguments']['label'] );
});
Carbon_Fields::extend( Wa_Advanced_Pill_List_Field::class, function( $container ) {
	return new Wa_Advanced_Pill_List_Field( $container['arguments']['type'], $container['arguments']['name'], $container['arguments']['label'] );
});
Carbon_Fields::extend( Wa_Toggle_Field::class, function( $container ) {
    return new Wa_Toggle_Field( $container['arguments']['type'], $container['arguments']['name'], $container['arguments']['label'] );
});
Carbon_Fields::extend( Wa_Schema_Info_Field::class, function( $container ) {
    return new Wa_Schema_Info_Field( $container['arguments']['type'], $container['arguments']['name'], $container['arguments']['label'] );
});
Carbon_Fields::extend( Wa_Toggle_Button_List_Field::class, function( $container ) {
    return new Wa_Toggle_Button_List_Field( $container['arguments']['type'], $container['arguments']['name'], $container['arguments']['label'] );
});
Carbon_Fields::extend( Wa_Text_Field::class, function( $container ) {
    return new Wa_Text_Field( $container['arguments']['type'], $container['arguments']['name'], $container['arguments']['label'] );
});
Carbon_Fields::extend( Wa_Profiled_Settings_Field::class, function( $container ) {
    return new Wa_Profiled_Settings_Field( $container['arguments']['type'], $container['arguments']['name'], $container['arguments']['label'] );
});
Carbon_Fields::extend( Wa_Blocks_Sequence_Field::class, function( $container ) {
    return new Wa_Blocks_Sequence_Field( $container['arguments']['type'], $container['arguments']['name'], $container['arguments']['label'] );
});