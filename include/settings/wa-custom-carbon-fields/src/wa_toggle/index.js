import { React } from "react";
import WaPersonalizedComponent from "../wa_personalized_component";
import WaToggleCheckbox from "../wa_toggle_checkbox";
import "./style.scss";

class WaToggle extends WaPersonalizedComponent {
	
	constructor(props) {
		super(props);
        this.onChange = this.onChange.bind(this);
	}

    onPersonalizedChanged(personalized, commonValue){
		if(!personalized)
            this.props.onChange(this.props.id, commonValue);
	}

    onChange(id, val){
        this.setPersonalized();
        this.props.onChange(this.props.id, this.getValueToActivate(val));
    }

	render() {
		const { id, name, value, field } = this.props;
        return (
            <div ref={this.rootRef} className={this.getPersonalizedClass()}>
                <WaToggleCheckbox 
                    context={field.context}
                    value={value} 
                    field={{label: field.label, description: field.description}} 
                    id={id + "_toggle"} name={name + "_toggle"} 
                    onChange={this.onChange}
                    {...this.resetProps}
                />
                <input type="hidden" id={id} name={name} value={this.getValueToSave(value)} />
            </div>
		);
	}
}

export default WaToggle;