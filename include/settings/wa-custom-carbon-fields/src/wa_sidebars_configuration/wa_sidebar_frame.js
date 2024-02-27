import { React } from "react";
import { Component } from '@wordpress/element';
import classes from "classnames";
import IconButton from "../icon_button";

class WaSidebarFrame extends Component {

	constructor(props) {
        super(props);
        this.onVisibilityChange = this.onVisibilityChange.bind(this);
	}

    onVisibilityChange(){
        this.props.onChange({ ...this.props.config, visible: !this.props.config.visible });
    }

    retrieveFrameBlocks(config){
        if(typeof config == "object")
            config = config.lines;
        if(!config || !config.length)
            return "Содержимое не заполнено";
        let lines = [];
        config.forEach(l => {
            if(!l.items.length)
                return;
            let line = l.items.map(i => !i ? null : <div className="frame-element">{i.title}</div>).filter(e => e != null);
            let horizontal = this.props.type == "header" || this.props.type == "footer";
            if(line.length)
                lines.push(<div className={classes("frame-elements-line", horizontal ? "horizontal" : "vertical")} 
                                style={!horizontal ? {flexDirection: "column"} : {}}>
                        {line}
                    </div>);
        });
        if(lines.length == 0)
            return "Содержимое не заполнено";
        return <div className="frame-elements-container">{lines}</div>;
    }    

	render() {
        let { type, config, tooltipPos } = this.props;
        let className = type.replace("_", "-");
        let content = this.retrieveFrameBlocks(config);
        let emptyContent = typeof content == "string";
        let frameTitle = type == "header" ? "Header" : (type == "footer" ? "Footer" : "Сайдбар");
        return (
            <div className={classes("wa-sidebar-frame", className, {"active": config.visible})}>
                <div className="wa-optional-buttons-panel">
                    <IconButton tooltip="Настройки" type="settings" tooltipPos={tooltipPos ?? "top"} onClick={this.props.onSettingsClick} />
                    <IconButton tooltip={config.visible ? "Скрыть" : "Показать"} tooltipPos={tooltipPos ?? "top"} type={config.visible ? "visible" : "hidden"} onClick={this.onVisibilityChange} />
                </div>
                {
                    emptyContent ?                 
                        <div className="whole-centered-descr">
                            {!config.visible ? <div>{frameTitle} не активен</div> : ""}
                            {content}
                        </div>
                        :
                        !config.visible ?
                            <>
                                {content}
                                <div className="whole-centered-descr overlay">
                                    <div>{frameTitle} не активен</div>
                                </div>
                            </>
                            :
                            content
                }
            </div>
        );
	}
}

export default WaSidebarFrame;