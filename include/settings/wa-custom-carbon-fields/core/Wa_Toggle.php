<?php

namespace Custom_Carbon_Fields;

class Wa_Toggle_Field extends Wa_Custom_Carbon {

    private $description;

    public function set_value_from_input( $input ) {
		parent::set_value_from_input( $input );
        if(!$this->personalized)
            return;
        $value = $this->get_value();
        if($value == "on" || $value == "true" || $value == 1)
            $value = true;
        else
            $value = false;
		$this->set_value( $value );
	}

	public function to_json( $load ) {
		$field_data = parent::to_json( $load );
        $value = $field_data["value"];
        if($value === "on" || $value === "true" || $value === 1 || $value === true)
            $value = true;
        elseif(empty($value) || $value === "" || $value === "false" || $value === 0 || $value === false)
            $value = false;
        $field_data["value"] = $value;
        return array_merge($field_data, ["description" => $this->description]);
	}

    function set_description($description) {
        $this->description = strval($description);
        return $this;
    }
}
