<?php

namespace Custom_Carbon_Fields;

class Wa_Text_Field extends Wa_Custom_Carbon {

    private $description;
    private $textarea_mode = false;

    public function set_value_from_input( $input ) {
        parent::set_value_from_input( $input );
        if(!$this->personalized)
            return;
        $value = $this->get_value();
        $this->set_value( $value );
    }

    public function to_json( $load ) {
        $field_data = parent::to_json( $load );
        return array_merge($field_data, [
            "description" => $this->description,
            "textarea_mode" => $this->textarea_mode
        ]);
    }

    function set_description($description) {
        $this->description = strval($description);
        return $this;
    }

    function set_textarea_mode($mode){
        $this->textarea_mode = $mode;
        return $this;
    }
}
