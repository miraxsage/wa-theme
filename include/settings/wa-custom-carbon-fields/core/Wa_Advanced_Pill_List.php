<?php

namespace Custom_Carbon_Fields;

class Wa_Advanced_Pill_List_Field extends Wa_Custom_Carbon {

	protected $sortable = true;
    protected $label = true;
    protected $description = true;
    protected $fullwidth = true;
    protected $choices = [];

	public function set_value_from_input( $input ) {
		parent::set_value_from_input( $input );
        if(!$this->personalized)
            return;
        $value = $this->get_value();
		$this->set_value( $value );
	}

	public function to_json( $load ) {
		$field_data = parent::to_json( $load );
        $saved_choices = explode(",", $this->get_value());
        if($this->sortable) {
            foreach ($saved_choices as $saved_choice) {
                $keyval = explode(":", esc_attr($saved_choice));
                $key = $keyval[0];
                $val = count($keyval) > 1 && $keyval[1] == 1;
                if(isset($this->choices[$key])) {
                    if(is_array($this->choices[$key]))
                        $reordered_choices[$key] = array_merge($this->choices[$key], ["checked" => $val]);
                    else
                        $reordered_choices[$key] = ["label" => $this->choices[$key], "checked" => $val];
                }
            }
            foreach($this->choices as $key => $val){
                if(!isset($reordered_choices[$key])) {
                    $reordered_choices[$key] = is_array($val) ? $val : ["label" => $val, "checked" => true];
                }
            }
        }
        else {
            $reordered_choices = $this->choices;
        }
		$field_data = array_merge( $field_data, array(
			'sortable' => $this->sortable,
            'label' => $this->label,
            'description' => $this->description,
            'fullwidth' => $this->fullwidth,
            'reordered_choices' => $reordered_choices
		) );
		return $field_data;
	}

	function set_sortable( $sortable = true ) {
		$this->sortable = $sortable === true;
		return $this;
	}
    function set_fulwidth( $fullwidth = true ) {
        $this->fullwidth = $fullwidth === true;
        return $this;
    }
    function set_label($label) {
        $this->label = strval($label);
        return $this;
    }
    function set_description($description) {
        $this->description = strval($description);
        return $this;
    }
    function set_choices( $choices ) {
        if(!is_array($choices))
            return $this;
        $ch = [];
        foreach (array_keys($choices) as $k){
            if(!is_numeric($k))
                $ch[$k] = $choices[$k];
        }
        if(count($ch) > 0)
            $this->choices = $ch;
        return $this;
    }
}
