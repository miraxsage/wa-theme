import { React } from "react";
import { Component } from '@wordpress/element';
import classes from "classnames";
import "./style.scss";
import WaPersonalizedComponent from "../wa_personalized_component";
import WaToggleButton from "../wa_toggle_button";
import WaResetOptionsHeader from "../wa_reset_options_header";

class WaToggleButtonsList extends WaPersonalizedComponent {

	constructor(props) {
		super(props);
        this.id = props.id ?? (Math.random() + 1).toString(36).substring(2, 10);
        try{
            if(!props.value)
                throw "Empty";
        }
        catch{ }
        this.onChange = this.onChange.bind(this);
	}

    currentValue(){
        let val = "";
        for(let button of this.buttons)
            val += (val ? "," : "") + button.key + ":" + (button.act ? 1 : 0);
        return val;
    }

    onChange(id, val){
        this.setPersonalized();
        if(this.props.field.mode == "radio" && !val)
            return;
        const key = id.replace(this.id + "_", "");
        this.buttons = this.buttons.map(b => {
            if(b.key == key)
                return { ...b, act: val == true };
            if(this.props.field.mode == "radio")
                return { ...b, act: val == false };
            return b;
        });
        this.props.onChange(this.id, this.currentValue());
    }

    onPersonalizedChanged(personalized, commonValue){
		if(!personalized)
            this.props.onChange(this.id, commonValue);
	}

	render() {
        const { name, value, withoutHeader, startCorners, style, endCorners, field: { description, label, context } } = this.props;
        if(!value)
            return "Empty params";
        this.buttons = JSON.parse(this.props.field.buttons);
        let toggles = value.split(",");
        let buttons = [];
        toggles.forEach(element => {
            let pair = element.split(":");
            let button = this.buttons.find(b => b.key == pair[0]);
            if(typeof button !== "undefined"){
                button.act = pair[1] == '1'; 
                buttons.push(button);
            }
        });
        this.buttons = buttons;
        return (
            <div ref={this.rootRef} style={style} class={classes("wa-toggle-button-list", this.getPersonalizedClass())}>
                <WaResetOptionsHeader collapse={withoutHeader} label={label} {...this.resetProps} context={context}>
                    {description ? <em className="cf-field__help wa-toggle-buttons-list__description">{description}</em> : ""}
                    {buttons.map((b, i) => 
                        <WaToggleButton 
                            ownedByList={true}
                            firstInList={i == 0 && !startCorners}
                            lastInList={i == buttons.length - 1 && !endCorners}
                            hasGutter={b.act && i < buttons.length - 1 && buttons[i + 1].act}
                            onChange={this.onChange} 
                            checked={b.act} 
                            id={this.id + "_" + b.key} 
                            label={b.text} />)
                    }
                    <input type="hidden" id={this.id} name={name} value={this.getValueToSave(value)} /> 
                </WaResetOptionsHeader>
            </div>
		);
	}
}

export default WaToggleButtonsList;