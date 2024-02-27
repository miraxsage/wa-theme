import { React, createRef } from "react";
import { Component } from '@wordpress/element';
import WaOptionsButton from '../wa_options_button';
import "./style.scss";

class WaToggleCheckbox extends Component {
    rootRef = createRef();
	constructor(props) {
		super(props);
        this.id = props.id;
        if(!props.id)
            this.id = (Math.random() + 1).toString(36).substring(2, 10);
	}
    componentDidMount() {
        if(!this.props.saveParentHeader && typeof jQuery !== "undefined")
            jQuery(this.rootRef.current).closest(".cf-field").find(".cf-field__head").remove();
    }
	render() {
		let { name, value, hideOptions, onReset, onTabReset, onFullReset, field, context, withoutHeader, style } = this.props;
        return (
            <div ref={this.rootRef} className="wa-toggle" style={style}>
                <div className="wa-toggle__header">
                    {withoutHeader ? null :
                        <WaOptionsButton 
                            hide={hideOptions} onReset={onReset} onTabReset={onTabReset} onFullReset={onFullReset} context={context}
                            style={{"margin": (hideOptions ? "0px" : "0px 4px 0px 0px"), 
                                    "position": "relative", 
                                    "top": "1px"}} /> }
                    <label class="cf-field__label" style={{margin: "0px", padding: "1px 0px 0px 0px"}}>{field.label}</label>
                    <div class="toggle-switch">
                        <input type="checkbox"
                            className="toggle-switch-checkbox"
                            id={this.id}
                            name={name}
                            checked={value}
                            onChange={(e) => this.props.onChange(this.id, e.target.checked)} />
                        <label class="toggle-switch-label" for={this.id}>
                            <span class="toggle-switch-inner"></span>
                            <span class="toggle-switch-switch"></span>
                        </label>
                    </div>
                </div>
                {field.description ? <em className="cf-field__help wa-toggle__description">{field.description}</em> : ""}
            </div>
		);
	}
}

export default WaToggleCheckbox;