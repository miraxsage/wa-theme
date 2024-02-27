import { React, createRef } from "react";
import { Component } from '@wordpress/element';
import "./style.scss";
/*  This parent personolizing component helps his inherit components to reset their settings
    to common settings. For that:
    1) extend your component by this one
    2) first of all in you constructor call parent one over "super(props)"
    3) in render function: - add parent personolizing class to your component root over "this.getPersonalizedClass()"
                           - set ref={this.rootRef} on parent element
    4) bind reset options for your instance of reset button:
       {...this.resetProps}
    5) set saving value to hidden field over parent function this.getValueToSave(this.props.value) that resets value if setting were reset
    6) add onPersonalizedChanged handler for setting up default (common) value from common settings:
    onPersonalizedChanged(personalized, commonValue){
		if(!personalized)
			this.setState({items: this.getReorderItemsByValue(commonValue)});
	}
    7) notify parent component about personilizing setting, when user change it by launch this.setPersonalized()
       for changing current mode to "personolized"props

    8) also you can use getValueToActivate in your child elements to get the save value (not changed) that your control have
       if it is changing from common unpersonalized state or you can use nonPersonalizedMoment predicate to detect moment before
       changing setting, when common (non personalized) mode is active yet
*/
class WaPersonalizedComponent extends Component {

    rootRef = createRef();

    constructor(props) {
		super(props);
        this.state = { usePersonalSetting: true };
        this.personalModeEnabled = ("personal_mode" in props.field && props.field.personal_mode == true);
        this.commonValue = props.field.common_value;
        if(this.personalModeEnabled && (props.value === null || props.value === "[!discard personal]" || props.field.value === "[!discard personal]")){
            props.value = props.field.common_value;
            this.state.usePersonalSetting = false;
        }
        if(!this.personalModeEnabled){
            this.state.usePersonalSetting = false;
        }
        this.setPersonalized = this.setPersonalized.bind(this);
        this.resetPersonalized = this.resetPersonalized.bind(this);
        this.onTabReset = this.onTabReset.bind(this);
        this.onTabResetEventHandler = this.onTabResetEventHandler.bind(this);
        this.onFullReset = this.onFullReset.bind(this);
        this.onFullResetEventHandler = this.onFullResetEventHandler.bind(this);
        document.addEventListener("resetTabWaPersonalSettings", this.onTabResetEventHandler);
        document.addEventListener("resetAllWaPersonalSettings", this.onFullResetEventHandler);
	} 

    componentDidMount(){
        this.newState = {};
        if(this.rootRef.current){
            this.tab = undefined;
            this.container = jQuery(this.rootRef.current).closest(".cf-container");
            this.container = this.container.length == 0 ? undefined : this.container[0];
            let tabContainer = jQuery(this.rootRef.current).closest(".cf-container__fields");
            if(tabContainer.length == 0)
                return;
            let index = -1;
            tabContainer.parent().find(".cf-container__fields").each(function(i){
                if(tabContainer[0] === this){
                    index = i;
                    return false;
                }
            });
            if(index < 0)
                return;
            let tabElement = tabContainer.siblings(".cf-container__tabs").first().find(".cf-container__tabs-item:nth-child(" + (index + 1) + ") button");
            if(tabElement.length == 0)
                return;
            this.tab = tabElement.html();
        }
    }

    componentDidUpdate(){
        this.newState = {};
    }

    setState(newState){
        newState = { ...this.state, ...this.newState, ...newState };
        this.newState = { ...this.newState, ...newState  };
        super.setState(newState);
    }

    getPersonolizedValue(val){
        if(this.personalModeEnabled && !this.state.usePersonalSetting)
            return this.commonValue;
        return val;
    }

    getValueToSave(val){
        if(!this.personalModeEnabled || this.state.usePersonalSetting)
            return val;
        return "[!discard personal]";
    }

    getValueToActivate(newval, oldval = undefined){
        if(this.nonPersonalizedMoment()){
            if(oldval !== undefined)
                return oldval;
            return this.props.value;
        }
        return newval;
    }

    nonPersonalizedMoment(){
        return this.personalModeEnabled && !this.state.usePersonalSetting;
    }    

    getPersonalizedClass(){
        if(!this.personalModeEnabled || this.state.usePersonalSetting)
            return "wa-personalized-component";
        return "wa-personalized-component--reset";
    }

    isPersonalized(){
        return this.personalModeEnabled && this.state.usePersonalSetting;
    }


    setPersonalized(){
        if(!this.personalModeEnabled || this.state.usePersonalSetting)
            return;
        this.setState({usePersonalSetting: true});
        if(typeof this.onPersonalizedChanged !== "undefined")
            this.onPersonalizedChanged(true, this.commonValue);
    }

    resetPersonalized(){
        if(!this.personalModeEnabled || !this.state.usePersonalSetting)
            return;
        this.setState({usePersonalSetting: false});
        if(typeof this.onPersonalizedChanged !== "undefined")
            this.onPersonalizedChanged(false, this.commonValue);
    }

    onTabReset(){
        if(!this.personalModeEnabled)
            return;
        document.dispatchEvent(new CustomEvent("resetTabWaPersonalSettings", { detail: this.tab }));
    }

    onTabResetEventHandler(e){
        if(typeof e.detail !== "undefined" && e.detail){
            if(this.tab && this.tab === e.detail)
            this.resetPersonalized();
        }
    }

    onFullReset(){
        if(!this.personalModeEnabled)
            return;
        document.dispatchEvent(new CustomEvent("resetAllWaPersonalSettings", { detail: this.container }));
    }

    onFullResetEventHandler(e){
        if(typeof e.detail !== "undefined" && e.detail){
            if(this.container && this.container === e.detail)
            this.resetPersonalized();
        }
    }

    get resetProps(){
        if(!this._resetProps)
            this._resetProps = {
                onReset: this.resetPersonalized,
                onTabReset: this.onTabReset,
                onFullReset: this.onFullReset
            };
        this._resetProps.hideOptions = !this.isPersonalized();
        return this._resetProps;
    }
}

export default WaPersonalizedComponent;