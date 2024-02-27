import { useState } from "react";
import { Component } from '@wordpress/element';
import classes from "classnames";
import Info from "../info";
import IsoGutenberg from "./iso_gutenberg";


export default class WaWidgetEditor extends Component{
    constructor(props){
        super(props);
        this.html = this.props.widget?.html ?? "";
        this.state = { config: { title: "", descr: "", html: "" }, error: null };
        if(this.props.widget)
            Object.assign(this.state.config, this.props.widget ?? {});
        this.onSave = this.onSave.bind(this);
        this.setConfig = this.setConfig.bind(this);
    }
    setConfig(config){
        let newState = {...this.state};
        newState.config = Object.assign(newState.config, config);
        newState.error = null;
        this.setState(newState);
    }
    onSave(){
        if(!this.state.config.title){
            this.setState({...this.state, error: "Укажите название виджета"});
            return;
        }
        if(this.props.onSave)
            this.props.onSave({...this.state.config, html: this.html});
    }

    render(){
        let { title, descr } = this.state.config;
        return <div className="wa-widget-editor-container">
            <input type="text" style={{width:"100%"}} placeholder="Название виджета" value={title} onChange={(e) => this.setConfig({title: e.target.value})} />
            <textarea value={descr} rows="2" style={{width:"100%"}} placeholder="Описание" onChange={(e) => this.setConfig({descr: e.target.value})}></textarea>
            <IsoGutenberg content={this.state.config.html} onChange={html => this.html = html} />
            {this.state.error && <Info style={{width:"100%", boxSizing: "border-box"}} type="error">{this.state.error}</Info>}
            <button style={{marginLeft: "auto", marginTop: "1px", display: "block"}} className="button button-primary" onClick={this.onSave}>Сохранить</button>
        </div>
    }
}