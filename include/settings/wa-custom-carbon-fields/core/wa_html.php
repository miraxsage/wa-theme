<?php

namespace Custom_Carbon_Fields;

class Wa_Html_Field extends Wa_Custom_Carbon {

    protected $html = "";
    protected $mode = "";
    protected $save_header = false;
    protected $save_divider = false;

    public function set_value_from_input( $input ) {
        parent::set_value_from_input( $input );
        $value = $this->get_value();
        $this->set_value( $value );
    }

    public function to_json( $load ) {
        $field_data = parent::to_json( $load );
        $field_data = array_merge($field_data, [
           "html" => $this->html,
           "mode" => $this->mode,
           "saveHeader" => $this->save_header,
           "saveDivider" => $this->save_divider
        ]);
        return $field_data;
    }

    function set_mode($mode) {
        $this->mode = strval($mode);
        return $this;
    }
    function set_html($html) {
        $this->html = strval($html);
        return $this;
    }
    function set_save_header($save){
        $this->save_header = $save;
        return $this;
    }
    function set_save_divider($save){
        $this->save_divider = $save;
        return $this;
    }
}
