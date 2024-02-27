import { React, createRef } from "react";
import { Component } from '@wordpress/element';
import WaOptionsButton from '../wa_options_button';
import "./style.scss";

class WaResetOptionsHeader extends Component {

    rootRef = createRef();

    componentDidMount() {
        if(typeof jQuery !== "undefined")
            jQuery(this.rootRef.current).closest(".cf-field").find(".cf-field__head").remove();
    }

    render(){
        let { hideOptions, onReset, onTabReset, onFullReset, label, children, context, collapse } = this.props;
        return <>
            <div className="wa-reset-header" ref={this.rootRef} style={{display: collapse ? "none" : "block"}}>
                {typeof onReset !== "undefined" || typeof onFullReset !== "undefined" ?
                    <WaOptionsButton 
                        context={context}
                        hide={hideOptions} 
                        onReset={onReset} 
                        onTabReset={onTabReset}
                        onFullReset={onFullReset}
                        style={{"margin": this.props.hideOptions ? 0 : "0px 5px 0px 0px", display: "inline"}} /> : ""}
                <label class="cf-field__label" style={{display:"inline"}}>{label}</label>
            </div>
            {children}
        </>
    }
    
}

export default WaResetOptionsHeader;