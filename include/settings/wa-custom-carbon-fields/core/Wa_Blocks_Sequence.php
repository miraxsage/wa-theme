<?php

namespace Custom_Carbon_Fields;

class Wa_Blocks_Sequence_Field extends Wa_Advanced_Pill_List_Field {
    function get_reordered_choices($sequence){
        $saved_choices = explode(",", $sequence);
        foreach ($saved_choices as $saved_choice) {
            $keyval = explode(":", esc_attr($saved_choice));
            $key = $keyval[0];
            $val = count($keyval) > 1 && $keyval[1] == 1;
            $block_match = null;
            if(isset($this->choices[$key]) || preg_match("/^block_(\\d+)$/", $key, $block_match) === 1) {
                if(isset($block_match)){
                    $reordered_choices[$key] = ["label" => "Блок ".$block_match[1], "checked" => $val];
                }
                else {
                    if(is_array($this->choices[$key]))
                        $reordered_choices[$key] = array_merge($this->choices[$key], ["checked" => $val]);
                    else
                        $reordered_choices[$key] = ["label" => $this->choices[$key], "checked" => $val];
                }
            }
        }
        foreach($this->choices as $key => $val){
            if(!isset($reordered_choices[$key])) {
                $reordered_choices[$key] = is_array($val) ? $val : ["label" => $val, "checked" => true];
            }
        }
        return $reordered_choices;
    }
    
    public function to_json( $load ) {
		$field_data = parent::to_json( $load );
        $value = $field_data["value"];
        $reordered_choices = [];
        
        if($value != "[!discard personal]"){
            $value_obj = json_decode($value);
            $reordered_choices = $this->get_reordered_choices($value_obj->sequence);
        }

        if($this->is_personal_setting){
            $common_value_obj = json_decode($field_data["common_value"]);
            $common_reordered_choices = $this->get_reordered_choices($common_value_obj->sequence); 
            $field_data = array_merge( $field_data, array(
                'common_reordered_choices' => $common_reordered_choices
            ) );
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
}
