<?php

namespace Custom_Carbon_Fields;

require_once(__DIR__."/wa_isolated_gutenberg/import.php");

class Wa_Sidebars_Configuration_Field extends Wa_Custom_Carbon {

    private $description = "";

    public function __construct($type, $name, $label)
    {
        parent::__construct($type, $name, $label);
        if(isset($_REQUEST['page']) && preg_match("/wa-widgets-settings\\.php/", $_REQUEST['page']) === 1)
            wa_import_isolated_gutenberg();
    }

    public function set_value_from_input( $input ) {
        parent::set_value_from_input( $input );
        $value = $this->get_value();
        $this->set_value( $value );
    }

    public function to_json( $load ) {
        $field_data = parent::to_json( $load );
        $field_data = array_merge($field_data, [
            "description" => $this->description
        ]);
        return $field_data;
    }

    public function set_description($description){
        $this->description = $description;
        return $this;
    }
}
