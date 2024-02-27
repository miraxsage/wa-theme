<?php

namespace Custom_Carbon_Fields;

class Wa_Schema_Info_Field extends Wa_Custom_Carbon {

    protected $description = "";
	protected $default_text = "";
    protected $mode = "scope";

    function set_description($description) {
        $this->description = strval($description);
        return $this;
    }

	function set_default_text($default_text) {
        $this->default_text = strval($default_text);
        return $this;
    }

    function set_mode($mode) {
        if($mode == "scope" || $mode == "property")
            $this->mode = $mode;
        return $this;
    }

    public function set_value_from_input( $input ) {
        parent::set_value_from_input( $input );
        if(!$this->personalized)
            return;
        $value = $this->get_value();
        try{
            $value = json_decode($value);
        }
        catch(Exception $exc){
            $value = (object)["act" => true, "text" => $this->default_text ?? ""];
        }
        if(empty($value->text))
            $value->text = $this->default_text;
        $this->set_value( json_encode($value) );
    }

	public function to_json( $load ) {
		$field_data = parent::to_json( $load );
        $field_data = array_merge( $field_data, array(
            'label' => $this->label,
            'mode' => $this->mode,
            'description' => $this->description,
			'default_text' => $this->default_text
		) );
		return $field_data;
	}
}
