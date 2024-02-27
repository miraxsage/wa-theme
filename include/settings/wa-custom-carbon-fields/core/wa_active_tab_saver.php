<?php

namespace Custom_Carbon_Fields;

class Wa_Active_Tab_Saver_Field extends Wa_Custom_Carbon {

    private $container = "";

    public function set_value_from_input( $input ) {
        parent::set_value_from_input( $input );
        $value = $this->get_value();
        $this->set_value( $value );
    }
    public function to_json( $load ) {
        $field_data = parent::to_json( $load );
        return array_merge($field_data, [
            "container" => $this->container
        ]);
    }
    public function set_container($container){
        if(is_string($container) && !empty($container))
            $this->container = $container;
        return $this;
    }
}
