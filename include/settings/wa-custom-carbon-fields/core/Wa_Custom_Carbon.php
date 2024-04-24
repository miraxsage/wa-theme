<?php


namespace Custom_Carbon_Fields;

class Wa_Custom_Carbon extends \Carbon_Fields\Field\Field {

    protected $theme_syncronized = false;
    protected $is_personal_setting = false;
    protected $personalized = false;

    public static function field_type_activated() {
        $dir = \Wa_Custom_Carbon_Fields\DIR . '/languages/';
        $locale = get_locale();
        $path = $dir . $locale . '.mo';
        load_textdomain( 'wa-custom-carbon', $path );
    }

    public static function admin_enqueue_scripts() {
        $root_uri = \Carbon_Fields\Carbon_Fields::directory_to_url( \Wa_Custom_Carbon_Fields\DIR );

        $debug = defined( 'CF_SCRIPT_DEBUG' ) && CF_SCRIPT_DEBUG;

        wp_enqueue_style(
            'wa-custom-carbon-fields',
            $root_uri . '/build/bundle' . ($debug ? '' : '.min') . '.css'
        );

        wp_enqueue_script(
            'wa-custom-carbon-fields',
            $root_uri . '/build/bundle' . ($debug ? '' : '.min') . '.js'.($debug ? "?r=".rand() : ""),
            array( 'carbon-fields-core' )
        );
    }

    public function set_value($val){
        parent::set_value($val);
        $this->personalized = ($val !== "[!discard personal]");
    }

    public function set_value_from_input( $input ) {
        parent::set_value_from_input( $input );
        $value = $this->get_value();
        if($this->is_personal_setting && $value === "[!discard personal]"){
            $this->set_value(null);
            $this->personalized = false;
            return;
        }
        $this->set_value( $value );
        if($this->theme_syncronized)
            \WaThemeSettings::get()->set_setting($this->base_name, $value);
    }

    public function to_json( $load ) {
        $field_data = parent::to_json( $load );
        if($this->theme_syncronized){
            $theme_val = \WaThemeSettings::get()->get_setting($this->base_name);
            if($theme_val === null)
                $theme_val = \WaThemeSettings::get()->get_setting_default($this->base_name);
            if($theme_val !== null) {
                $this->set_value($theme_val);
                $field_data["value"] = $theme_val;
            }
        }
        if($this->is_personal_setting){
            $field_data["personal_mode"] = true; 
            global $post;
            $edit_post_or_page = null;
            if($post && $post instanceof \WP_Post && ($post->post_type == "page" || $post->post_type == "post"))
                $edit_post_or_page = $post;
            $field_data["common_value"] = \WaThemeSettings::get()->get_setting($this->base_name, true, true, false, $edit_post_or_page);
            if(empty($field_data["value"]))
                $field_data["value"] = "[!discard personal]";
        }
        return $field_data;
    }

    public function set_is_personal_setting($is_personal){
        $this->is_personal_setting = $is_personal;
    }

    public function set_theme_syncronized($sync){
        $this->theme_syncronized = $sync;
    }
}
