import { React } from "react";
import { Component } from '@wordpress/element';
import WaResetOptionsHeader from "../wa_reset_options_header";
import WaPersonalizedComponent from "../wa_personalized_component";
import "./style.scss";

class WaText extends WaPersonalizedComponent {
	
	constructor(props) {
		super(props);
        this.state = { text: props.value };
        this.onChange = this.onChange.bind(this);
	}

    onPersonalizedChanged(personalized, commonValue){
		if(!personalized)
        this.props.onChange(this.props.id, commonValue);
	}

    onChange(e){
        this.setPersonalized();
        this.props.onChange(this.props.id, e.target.value);
    }

	render() {
		const { id, name, value, field } = this.props;
        return (
            <div className={this.getPersonalizedClass()} ref={this.rootRef}>
                <WaResetOptionsHeader label={field.label} {...this.resetProps} context={field.context}>
                    <input type="hidden" id={id} name={name} value={this.getValueToSave(value)} />
                    {field.textarea_mode ?
                        <textarea rows="5" class="cf-textarea__input" onChange={this.onChange}>{value}</textarea>
                        : 
                        <input type="text" class="cf-text__input" value={value} onChange={this.onChange}></input>}
                    {field.description ? <em className="cf-field__help wa-toggle__description">{field.description}</em> : ""}
                </WaResetOptionsHeader>
            </div>
		);
	}
}

export default WaText;