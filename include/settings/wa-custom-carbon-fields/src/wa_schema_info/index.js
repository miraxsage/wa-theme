import { React, createRef } from "react";
import { Component } from '@wordpress/element';
import WaToggleCheckbox from '../wa_toggle_checkbox';
import classes from 'classnames';
import WaPersonalizedComponent from "../wa_personalized_component";
import "./style.scss";

class WaSchemaInfo extends WaPersonalizedComponent {

    constructor(props) {
        super(props);
        let val = this.getVal();
        let field = this.props.field;
        let text = val.text && val.text.length > 0 ? val.text : (field.default_text && field.default_text.length > 0 ? field.default_text : "");
        val.text = text;
        this.props.value = JSON.stringify(val);
        this.getNewVal = this.getNewValRaw.bind(this);
        this.onChange = this.onChange.bind(this);
    }

    getVal() {
        let val = null;
        try {
            val = JSON.parse(this.props.value);
        }
        catch (e) { }
        if (!val)
            val = { act: true, text: this.props.field.default_text ?? "" };
        return val;
    }

    getNewValRaw(newAct = null, newText = null) {
        let oldVal = this.getVal();
        if (newAct == null)
            newAct = oldVal.act;
        if (newText == null)
            newText = oldVal.text;
        return JSON.stringify({ act: newAct, text: newText });
    }

    onPersonalizedChanged(personalized, commonValue) {
        if (!personalized) {
            this.props.onChange(this.props.id, commonValue);
        }
    }

    onChange(id, val) {
        let newval;
        if (typeof val == "boolean")
            newval = this.getValueToActivate(this.getNewValRaw(val));
        else
            newval = this.getNewValRaw(null, id.target.value);
        this.setPersonalized();
        this.props.onChange(this.props.id, newval);
    }

    render() {
        const { id, name, value, field } = this.props;
        let val = this.getVal();
        let text = val.text && val.text.length > 0 ? val.text : (field.default_text && field.default_text.length > 0 ? field.default_text : "");
        let res_value = JSON.stringify(val);
        return (
            <div ref={this.rootRef} className={classes("wa-schema-info", this.getPersonalizedClass())}>
                <WaToggleCheckbox
                    context={field.context}
                    value={val.act}
                    field={{ label: field.label }}
                    onChange={this.onChange}
                    id={id + "_toggle"} name={name + "_toggle"}
                    {...this.resetProps}
                />
                <div style={{ margin: "4px 0px 7px" }}>
                    {field.mode == 'scope' ? 'itemscope itemtype="https://schema.org/' : 'itemprop="'}
                    <b>{text && text.length > 0 ? text : "<val>"}</b>"
                </div>
                <input type="text" className="cf-text__input" value={val.text} onChange={this.onChange} />
                <input type="hidden" id={id} name={name} value={this.getValueToSave(res_value)} />
            </div>
        );
    }
}

export default WaSchemaInfo;