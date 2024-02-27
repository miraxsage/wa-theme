import { React } from "react";
import { Component } from '@wordpress/element';
import WaResetOptionsHeader from "../wa_reset_options_header";
import WaPersonalizedComponent from "../wa_personalized_component";
import WaTabs from "../wa_tabs/index";
import classes from "classnames";
import WaSidebarsFrames from "./wa_sidebars_frames";
import "./style.scss";
import WaFrameConfiguration from "./wa_frame_configuration";
import WaSidebarsProfileSelector from "./wa_sidebars_profile/wa_sidebars_profile_selector";

class WaSidebarsConfiguration extends WaPersonalizedComponent {
	
    sidebars = ["header", "footer", "inner_left", "inner_right", "outer_left", "outer_right"];

	checkStoreObj(store){
        if(!store || typeof store !== "object" || !(store instanceof Array))
            return false;
        return true;
    }

    fixAbsentKeys(){
        let store = this.config.find(c => c.key == this.state.chosenProfile).config;
        let hasAbsentKeys = false;
        this.sidebars.forEach(sbr => {
            if(store[sbr].lines.length > 0){
                store[sbr].lines.forEach((ln, i) => {
                    if(!ln.key){
                        store[sbr].lines[i].key = Math.floor(Math.random() * Date.now()).toString(16);
                        hasAbsentKeys = true;
                    }
                    ln.items.forEach((it, j) => {
                        if(!it.key){
                            store[sbr].lines[i].items[j].key = Math.floor(Math.random() * Date.now()).toString(16);
                            hasAbsentKeys = true;
                        }
                    })
                });
            }
        });
        return hasAbsentKeys;
    }

    constructor(props) {
		super(props);
        this.config = null;
        this.state = { chosenProfile: "common" };
        this.onSlidersConfigChanged = this.onSlidersConfigChanged.bind(this);
        this.onFrameConfigChanged = this.onFrameConfigChanged.bind(this);
        this.onChange = this.onChange.bind(this);
        this.frameConfigControl = this.frameConfigControl.bind(this);
	}
    onPersonalizedChanged(personalized, commonValue){
		if(!personalized)
            this.props.onChange(this.props.id, commonValue);
	}
    onSlidersConfigChanged(newConfig){
        this.onChange(this.config.map(c => c.key == this.state.chosenProfile ? {...c, config: newConfig } : c));
    }
    onFrameConfigChanged(frame_name){
        return (newConfig) =>
            this.onChange(this.config.map(c => c.key == this.state.chosenProfile ? {...c, config: { ...c.config, [frame_name]: newConfig }} : c));
    }
    onChange(newConfig, personalized = true){
        if(!newConfig.find(c => c.key == this.state.chosenProfile)){
            let chosenIndex = this.config.findIndex(c => c.key == this.state.chosenProfile);
            if(chosenIndex > 0)
                chosenIndex--;
            else
                chosenIndex++;
            this.setState({...this.state, chosenProfile: this.config[chosenIndex].key});
        }
        this.setPersonalized();
        this.config = newConfig;
        let newConfigJson = JSON.stringify(newConfig);
        if(newConfigJson != this.props.value)
            this.props.onChange(this.props.id, JSON.stringify(newConfig));
        else
            this.setState({});
    }

    frameConfigControl(frame_name){
        return <WaFrameConfiguration
            name={frame_name}
            config={this.config.find(c => c.key == this.state.chosenProfile).config[frame_name]}
            onChange={this.onFrameConfigChanged(frame_name)}
        />
    }

	render() {
        try{
            if(!this.config)
                this.config = JSON.parse(this.props.value);
            if(this.state.chosenProfile > this.config.length){
                this.setState({...this.state, chosenProfile: this.config.length - 1});
                return;
            }
            if(this.fixAbsentKeys()){
                this.onChange(this.config, false);
                return;
            }
        }
        catch{}
        if(!this.checkStoreObj(this.config))
            this.config = null;
        if(!this.config)
            return "state hasn`t been configured";
        let slidersConfig = this.config.find(p => p.key == this.state.chosenProfile).config;
		const { id, name, value, field } = this.props;
        return (
            <div className={classes("wa-sidebar-configuration", this.getPersonalizedClass())} ref={this.rootRef}>
                <WaResetOptionsHeader label={field.label} {...this.resetProps} context={field.context}>
                    <input type="hidden" id={id} name={name} value={this.getValueToSave(value)} />
                    {field.description ? <span className="wa-advanced-pill-list__description">{field.description}</span> : ""}
                    <WaSidebarsProfileSelector 
                        config={this.config} 
                        chosenProfile={this.state.chosenProfile} 
                        onChange={this.onChange}
                        onSelect={(key) => this.setState({...this.state, chosenProfile: key})}
                    />
                    <WaTabs>
                        {{
                            "Каркас": ({onActiveTabChange}) => 
                                <WaSidebarsFrames config={slidersConfig} 
                                                  onChange={this.onSlidersConfigChanged} 
                                                  onSettingsClick={(frame_name) => onActiveTabChange(frame_name)} 
                                />,
                            "Header": this.frameConfigControl("header"),
                            "Footer": this.frameConfigControl("footer"),
                            "Outer Left": this.frameConfigControl("outer_left"),
                            "Outer Right": this.frameConfigControl("outer_right"),
                            "Inner Left": this.frameConfigControl("inner_left"),
                            "Inner Right": this.frameConfigControl("inner_right")
                        }}
                    </WaTabs>
                </WaResetOptionsHeader>
            </div>
		);
	}
}

export default WaSidebarsConfiguration;