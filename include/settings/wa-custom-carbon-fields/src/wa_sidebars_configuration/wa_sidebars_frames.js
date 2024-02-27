import { React } from "react";
import { Component } from '@wordpress/element';
import WaSidebarFrame from "./wa_sidebar_frame";

class WaSidebarsFrames extends Component {

	constructor(props) {
        super(props);
        this.onChange = this.onChange.bind(this);
        this.frameProps = this.frameProps.bind(this);
	}

    onChange(frame_name){
        return (function(newFrameConfig){
            this.props.onChange({ ...this.props.config, [frame_name]: newFrameConfig });
        }).bind(this);
    }

    frameProps(frame_name){
        return {
            type: frame_name,
            config: this.props.config[frame_name],
            onChange: this.onChange(frame_name),
            onSettingsClick: () => this.props.onSettingsClick(frame_name.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' '))
        };
    }

	render() {
        return (
            <div className="wa-sidebars-frames_outer">
                <WaSidebarFrame tooltipPos="right" {...this.frameProps("outer_left")} />
                <div className="wa-sidebars-frames_middle">
                    <WaSidebarFrame tooltipPos="left" {...this.frameProps("header")} />
                    <div className="wa-sidebars-frames_inner">
                        <WaSidebarFrame tooltipPos="right" {...this.frameProps("inner_left")} />
                        <div className="wa-sidebars-frames-content">Content</div>
                        <WaSidebarFrame tooltipPos="left" {...this.frameProps("inner_right")} />  
                    </div>
                    <WaSidebarFrame tooltipPos="left" {...this.frameProps("footer")} />
                </div>
                <WaSidebarFrame tooltipPos="left" {...this.frameProps("outer_right")} />                  
            </div>
        );
	}
}

export default WaSidebarsFrames;