import { useState } from "react";
import { Component } from '@wordpress/element';
import classes from "classnames";
import Info from "../info";

function Widget({data, onClick, active}){
    return <div onClick={onClick} className={classes("wa-widget-item", {"active": active})}>
        <div className="wa-widget-item-title">{data.name}</div>
        <div className="wa-widget-item-descr">{data.description}</div>
    </div>;
}
export default class WidgetsList extends Component{
    constructor(props){
        super(props);
        this.state = { search: "" };
        this.onSearchChange = this.onSearchChange.bind(this);
        this.onWidgetChosen = this.onWidgetChosen.bind(this);
    }
    onSearchChange(e){
        this.setState({search: e.target.value});
    }
    onWidgetChosen(widget){
        if(this.props.onChosen)
            this.props.onChosen(widget);
    }
    render(){
        let widgets = this.props.widgets;
        if(this.state.search)
            widgets = this.props.widgets.filter(w => w.name.toLowerCase().indexOf(this.state.search.toLowerCase()) >= 0 || w.description.toLowerCase().indexOf(this.state.search.toLowerCase()) >= 0);
        return <div className="wa-widgets-list">
            <input type="text" value={this.state.search} onChange={this.onSearchChange} placeholder="Поиск" />
            <div style={{overflow: "auto", width: "445px", position: "relative"}}>
                { !widgets.length ? 
                      <div className="centered" style={{top: "25px"}}><Info type="info">Не найдено ни одного подходящего виджета</Info></div> 
                      : 
                      widgets.map(w => <Widget key={w.class} data={w} active={this.props.current && w.class == this.props.current.class} onClick={() => this.onWidgetChosen(w)} />) }
            </div>
        </div>;
    }
}