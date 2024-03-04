import { useState } from "react";
import { Component } from '@wordpress/element';
import classes from "classnames";
import Info from "../info";
import IconButton from "../icon_button";

function Resource({data, kind, onClick}){
    if(kind == "cats")
        return <div onClick={onClick} className={classes("wa-resource-list-item")}>
            <div className="wa-resource-list-item-title">{data.name}</div>
            <div className="wa-resource-list-item-descr">{"id: " + data.id + (data.description ? ", " : "") + data.description}</div>
        </div>;
    else
        return <div onClick={onClick} className={classes("wa-resource-list-item")}>
            <div className="wa-resource-list-item-title">{data.title.rendered}</div>
            <div className="wa-resource-list-item-descr">{"id: " + data.id + (isNaN(Date.parse(data.modified)) ? "" : ", изменено: " + new Date(data.modified).toLocaleString())}</div>
        </div>;
}
export default class ResourceChooser extends Component{
    constructor(props){
        super(props);
        let lazy = typeof(this.props.resources) == "function" && !this.props.kind;
        let resources = this.props.resources;
        let kind = this.props.kind ?? "";
        if(kind && kind.slice(-1) != "s")
            kind = kind + "s";
        this.kind = kind;
        if(kind)
            resources = window.wa.resources[kind];
        this.state = { filter: "", resources: lazy ? [] : resources, loading: lazy };
        this.onSearchChange = this.onSearchChange.bind(this);
        this.onResourceChosen = this.onResourceChosen.bind(this);
    }
    onSearchChange(e){
        this.setState({...this.state, filter: e.target.value});
    }
    onResourceChosen(resource){
        if(this.props.onChosen)
            this.props.onChosen(resource);
    }
    componentWillUnmount(){
        this._isMounted = false;
    }
    componentDidMount(){
        if(!this.state.loading)
            return;
        this._isMounted = true;
        this.props.resources().then(resourses => {
            if(!this._isMounted)
                return;
            this.setState({...this.state, loading: false, resourses});
        });
    }
    render(){
        if(this.state.loading)
            return "Загрузка...";
        let resources = !this.state.filter ? 
                        (this.state.resources ?? []) 
                        : 
                        this.state.resources.filter(w => {
                            let title = w.title ?? w.name;
                            if(typeof title == "object")
                                title = title.rendered;
                            return (w.id + " " + title + " " + (new Date(w.modified)).toLocaleString())
                                .toLowerCase().indexOf(this.state.filter.toLowerCase()) >= 0;
                        });
        return <div className="wa-resources-list">
            <div style={{display:"flex",alignItems:"center",gap:"5px"}}>
                <input type="text" value={this.state.search} onChange={this.onSearchChange} placeholder="Поиск" />
                <IconButton tooltip="Сбросить поиск" className="rotate-content-45" type="add" onClick={() => this.setState({...this.state, filter: ""})} />
            </div>
            <div style={{overflow: "auto", position: "relative"}}>
                { !resources.length ? 
                      <Info type="warning">Соответствующих поиску {this.kind == "posts" ? "записей" : (kind == "pages" ? "страниц" : "категорий")} не обнаружено</Info> 
                      : 
                      resources.map(w => <Resource key={w.id} data={w} kind={this.props.kind} onClick={() => this.onResourceChosen(w)} />) }
            </div>
        </div>;
    }
}