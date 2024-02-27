<?php

namespace Custom_Carbon_Fields;

class Wa_Toggle_Button_List_Field extends Wa_Custom_Carbon {

    protected $description = "";
	protected $mode = "";
    protected $buttons = [];

    function set_description($description) {
        $this->description = strval($description);
        return $this;
    }

	function set_mode($mode) {
        $this->mode = $mode == "radio" ? $mode : "checkbox";
        return $this;
    }

	public function set_value_from_input( $input ) {
		parent::set_value_from_input( $input );
        if(!$this->personalized)
            return;
        $value = $this->get_value();
        $buttons = explode(",", $value);
        for($i = 0; $i < count($buttons); $i++) {
            $kv = explode(":", $buttons[$i]);
            $buttons[$i] = ["key" => $kv[0], "act" => $kv[1] == 1];
        }
        if(empty($buttons)){
            $this->set_value( "" );
            return;
        }

        $has_checked = false;
        $added_keys = [];
        $valid_keys = array_keys($this->buttons);
        $valid_buttons = [];
        foreach ($buttons as $btn){
            if(array_key_exists("key", $btn) && in_array($btn["key"], $valid_keys) && !in_array($btn["key"], $added_keys)){
                $btn = ["key" => $btn["key"], "act" => $btn["act"] && ($this->mode != "radio" || !$has_checked)];
                $added_keys[] = $btn["key"];
                $valid_buttons[] = $btn;
                if($btn["act"])
                    $has_checked = true;
            }
        }

        if(count($valid_buttons) > 0 && !$has_checked && $this->mode == "radio")
            $valid_buttons[0]->act = true;
        $value = "";
        if(count($valid_buttons) > 0){
            foreach ($valid_buttons as $btn)
                $value .= (empty($value) ? "" : ",").$btn["key"].":".($btn["act"] ? 1 : 0);
        }
		$this->set_value( $value );
	}

	public function to_json( $load ) {
		$field_data = parent::to_json( $load );
        $value = $this->get_value();
        $buttons = [];
        if(empty($value) || $value == "[!discard personal]"){
            foreach ($this->buttons as $k => $v)
                $buttons[] = (object)["key" => $k, "text" => $v["text"], "act" => isset($v["act"]) && $v["act"]];
        }
        else{
            $pairs = explode(",", $this->get_value());
            foreach ($pairs as $pair){
                $keyval = explode(":", $pair);
                if(array_key_exists($keyval[0], $this->buttons))
                    $buttons[] = (object)["key" => $keyval[0], "text" => $this->buttons[$keyval[0]]["text"], "act" => $keyval[1] == 1];
            }
        }
        if($this->mode == "radio"){
            $has_checked = false;
            foreach($buttons as $btn){
                $btn->act = $btn->act && !$has_checked;
                if($btn->act)
                    $has_checked = true;
            }
            if(!$has_checked && count($buttons) > 0)
                $buttons[0]->act = true;
        }
        $field_data = array_merge( $field_data, array(
            'label' => $this->label,
            'buttons' => json_encode($buttons),
			'mode' => $this->mode,
            'description' => $this->description
		) );
		return $field_data;
	}

    function set_buttons( $buttons ) {
        if(!is_array($buttons))
            return $this;
        $btns = [];
        foreach (array_keys($buttons) as $k){
            if(!is_numeric($k))
                $btns[$k] = $buttons[$k];
        }
        if(count($btns) > 0)
            $this->buttons = $btns;
        return $this;
    }
}
