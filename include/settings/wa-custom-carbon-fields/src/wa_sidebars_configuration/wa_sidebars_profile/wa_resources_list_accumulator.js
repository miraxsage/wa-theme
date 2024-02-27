import { React, createRef } from "react";
import { Component } from '@wordpress/element';
import Info from "../../info";
import IconButton from "../../icon_button";
import "./style.scss";
import WaToggleButtonsList from "../../wa_toggle_buttons_list";
import ResourceChooser from "../wa_resource_chooser";

class WaResourceItem extends Component {
	constructor(props) {
        super(props);
	}
	render() {
        let { id, kind } = this.props;
        if(!kind)
            kind = "posts";
        if(kind.slice(-1) !== "s")
            kind = kind + "s";
        let data = window.wa.resources[kind].find(d => d.id == id);
        let title = data.title ?? data.name;
        if(typeof title == "object")
            title = title.rendered;
        return (
            <div className="wa-resource-item">
                <span>{title} ({data.id})</span>
                <IconButton style={{marginLeft: "5px"}} tooltip="Удалить" type="trash" onClick={this.props.onDelete ?? (() => { })} />
            </div>
        );
	}
}

class WaResourcesListAccumulator extends Component {
	constructor(props) {
        super(props);
        this.config = this.props.config;
        this.kind = this.props.kind;
        if(this.kind.at(-1) != "s")
            this.kind = this.kind + "s";
        let resourcesLoaded = Boolean(window.wa?.resources);
        this.state = { 
            resourcesLoaded, 
            resourceChoosing: false,
            filter: ""
        };
        this.onResourcesLoaded = this.onResourcesLoaded.bind(this);
        this.chooseResource = this.chooseResource.bind(this);
        this.cancelChoosing = this.cancelChoosing.bind(this);
        this.onResourceChosen = this.onResourceChosen.bind(this);
	}
    onResourcesLoaded(){
        let newState = {...this.state, resourcesLoaded: true};
        this.setState(newState);
    }
    componentWillUnmount(){
        document.removeEventListener("WaResources_Loaded", this.onResourcesLoaded);
    }
    componentDidMount(){
        if(!this.state.resourcesLoaded)
            document.addEventListener("WaResources_Loaded", this.onResourcesLoaded);
    }
    chooseResource(e){
        e.preventDefault(); 
        this.setState({...this.state, resourceChoosing: true});
    }
    cancelChoosing(e){
        e.preventDefault(); 
        this.setState({...this.state, resourceChoosing: false});
    }
    onResourceChosen(resource){
        this.setState({...this.state, resourceChoosing: false});
        this.onChange(null, [...this.config.ids.filter(id => id != resource.id), resource.id]);
    }
    getModeFromList(list){
        if(!list)
            return list;
        if(list.indexOf("include:1") >= 0)
            return "include";
        if(list.indexOf("exclude:1") >= 0)
            return "exclude";
        if(list.indexOf("none:1") >= 0)
            return "none";
        if(list.indexOf("0") >= 0)
            return "all";
        return list;
    }
    onChange(mode, ids){
        mode = this.getModeFromList(mode) ?? this.config.mode;
        ids = ids ?? this.config.ids;
        this.props.onChange({ mode, ids });
    }
	render() {
        if(!this.state.resourcesLoaded)
            return "Загрузка...";
        let kind = this.kind;
        this.config = {...this.props.config, ids: [...this.props.config.ids.filter(id => Boolean(window.wa.resources[this.kind].find(r => r.id == id)))] };
        let { resourceChoosing } = this.state;
        let { mode, ids } = this.config;
        ids = !this.state.filter ? ids : this.config.ids.filter(id => {
            let res = window.wa.resources[kind].find(r => r.id == id);
            let title = res.title ?? res.name;
            if(typeof title == "object")
                title = title.rendered;
            return res && (`${title} (${res.id})`).toLowerCase().indexOf(this.state.filter.toLowerCase()) >= 0; 
        });
        let readOnly = Boolean(this.props.readOnly);
        return (
            <>
                <div className="wa-resource-accumulator">
                    <WaToggleButtonsList 
                        withoutHeader={true} 
                        onChange={(id, val) => this.onChange(val)}
                        value={readOnly ? "all:1" : (["all", "include", "exclude", "none"].reduce((res, c, i) => `${res}${i ? "," : ""}${c}:${Number(c == mode)}`, ''))} 
                        style={{marginBottom:"5px"}}
                        field={{mode:"radio", buttons: readOnly ? `[{"key":"all","text":"Для всех","act":1}]` : `[{"key":"all","text":"Для всех","act":1},{"key":"include","text":"Для выбранных","act":0},{"key":"exclude","text":"Для всех, кроме выбранных","act":0},{"key":"none","text":"Ни для каких","act":0}]`}} />
                    <Info style={{padding: "4px 0px 9px 0px"}}>
                        {
                            mode == "all" ? "Данный профиль будет применяться на " + (kind.startsWith("post") ? "страницах всех записей" : kind.startsWith("page") ? "всех страницах" : "страницах любых архивов")
                            : mode == "include" ? "Данный профиль будет применяться только на " + (kind.startsWith("post") ? "страницах выбранных ниже записей" : kind.startsWith("page") ? "выбранных ниже страницах" : "страницах архивов выбранных ниже категорий")
                            : mode == "none" ? "Данный профиль не будет применяться ни на каких " + (kind.startsWith("post") ? "страницах записей" : kind.startsWith("page") ? "страницах" : "страницах архивов")
                            : "Данный профиль будет применяться на всех страницах, кроме " + (kind.startsWith("post") ? "выбранных ниже записей" : kind.startsWith("page") ? "выбранных ниже" : "архивов с выбранными ниже категорями")
                        }
                    </Info>
                    {
                    mode != "all" && mode != "none" ? 
                        <>
                            <div className="header">
                                <button className="button" style={{marginRight: "1px"}} onClick={this.chooseResource}>Добавить {kind.startsWith("post") ? "запись" : (kind.startsWith("page") ? "страницу" : "категорию")}</button>
                                <input type="text" value={this.state.filter} onChange={(e) => this.setState({...this.state, filter: e.target.value})} placeholder="Поиск в добавленных" />
                                <IconButton className="rotate-content-45" tooltip="Сбросить поиск" type="add" onClick={() => this.setState({...this.state, filter: ""})} />
                            </div> 
                            <div className="list" style={{display: "flex", flexWrap: "wrap", gap: "5px"}}>
                                {ids.length > 0 ? 
                                    ids.map(id => <WaResourceItem kind={kind} id={id} onDelete={() => this.onChange(mode, ids.filter(_id => _id != id))} />)
                                :
                                    <Info type={this.state.filter ? "warning" : "info"} style={this.state.filter ? {padding: "7px 9px"} : {padding: "4px 0px 9px 0px"}}>
                                        {!this.state.filter ? <>Не выбрано 
                                            {(kind.startsWith("post") ? " ни одной записи " : kind.startsWith("page") ? " ни одной страницы " : " ни одной категории ")}
                                            для фильтрации</>
                                            :
                                            <>Соответствующих поиску {kind == "posts" ? "записей" : (kind == "pages" ? "страниц" : "категорий")} не обнаружено</>
                                        }
                                    </Info>
                                }
                            </div>
                        </>
                    : null
                    }
                </div>
                {resourceChoosing && 
                <div className="resource-chooser-overlap">
                    <div style={{ display: "flex", flexDirection: "row", gap: "7px", alignItems: "center"}}>
                        <IconButton tooltip="Назад" tooltipPos="right" type="back-arrow" onClick={this.cancelChoosing} />
                        <div className="wa-title" style={{marginTop:"1px"}}>{`Выберите соответствующую ${kind.startsWith("post") ? 'запись' : (kind.startsWith("page") ? 'страницу' : 'категорию')} из списка для ее добавления в фильтр:`}</div>
                    </div>
                    <ResourceChooser kind={kind} onChosen={this.onResourceChosen} />
                    <button className="button button-outline" onClick={this.cancelChoosing} style={{justifySelf: "right"}}>Отмена</button>
                </div>}
            </>
        );
	}
}

export default WaResourcesListAccumulator;