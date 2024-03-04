import { React } from "react";
import { Component } from '@wordpress/element';
import classes from "classnames";
import WaToggleButtonsList from "../wa_toggle_buttons_list";
import Info from "../info";
import IconButton from "../icon_button";

class WaSizeControl extends Component{
    constructor(props) {
        super(props);
        this.state = { unitsArePx: true };
        this.unitsArePx = true;
        this.onChange = this.onChange.bind(this);
	}
    onChange(weight, units){
        let value = null;
        let newState = { unitsArePx: units.slice(0, 4) == "px:1" };
        this.setState(newState);
        if(!weight && weight !== "0")
            value = null;
        else{
            weight = parseFloat(weight);
            if(!weight && weight !== 0)
                value = null;
            else{
                value = weight + (newState.unitsArePx ? "px" : "%");
            }
        }
        if(this.props.value != value)
            this.props.onChange(value);
    }
    render(){
        let { value } = this.props;
        let unitsArePx = value ? value.slice(-1) != "%" : this.state.unitsArePx;
        let num = value ? value.slice(0, unitsArePx ? -2 : -1) : "";
        let toggles = "px:" + (unitsArePx ? 1 : 0) + ",percents:" + (unitsArePx ? 0 : 1);
        return (<div className="wa-size">
            <input type="number" value={num} onChange={(e) => this.onChange(e.target.value, toggles)} style={{...this.props.style, borderRadius:"4px 0px 0px 4px"}}/>
            <WaToggleButtonsList startCorners={true} onChange={(id, val) => this.onChange(num, val)} style={{marginLeft: "-1px"}} withoutHeader={true} value={toggles} field={{mode:"radio", buttons:`[{"key":"px","text":"px","act":1},{"key":"percents","text":"%","act":0}]`}} />
        </div>);
    }
}
class WaColorControl extends Component{
    constructor(props) {
        super(props);
	}
    render(){
        let { title, value, style, onChange } = this.props;
        return (<InlineControl style={{...style}} title={title}>
        <input type="color" value={value ?? "#ffffff"} onChange={(e) => onChange(e.target.value)} style={{ flexGrow: 1 }} />
        {value ? <IconButton tooltip="Очистить" type="trash" onClick={() => onChange(null)} style={{marginLeft: "3px"}} /> : null}
    </InlineControl>);
    }
}
class WaBorderControl extends Component{
    constructor(props) {
        super(props);
        this.onChange = this.onChange.bind(this);
	}
    onChange(newWeight, newColor){
        newWeight = parseFloat(newWeight);
        if(!newWeight)
            newWeight = "-";
        this.props.onChange(newWeight + "," + newColor);
    }
    render(){
        let { title, value, style, onChange } = this.props;
        let valels = value ? value.split(',') : ["-", "#ffffff"];
        return (<InlineControl title={title} style={{marginTop: "5px", ...style}}>
            <input type="number" value={valels[0] == "-" ? "" : valels[0]} onChange={(e) => this.onChange(e.target.value, valels[1])} style={{width: value ? "52px" : "66px",marginRight:"-2px",borderRadius:"4px 0px 0px 4px"}} />
            <input type="color" value={valels[1]} onChange={(e) => this.onChange(valels[0], e.target.value)} style={{flexGrow: 1, height: "30px", borderRadius: "0px 4px 4px 0px"}} />
            {value ? <IconButton type="trash" title="Очистить" onClick={() => onChange(null)} style={{marginLeft: "3px"}}/> : null}
        </InlineControl>);
    }
}
class WaMarginsControl extends Component{
    constructor(props) {
        super(props);
        this.state = this.extractState(this.props.value);
        this.errors = [false, false, false, false];
        this.onChange = this.onChange.bind(this);
        this.setStateByIndex = this.setStateByIndex.bind(this);
        this.ownRerender = false;
	}

    extractState(value){
        if(typeof value !== "string" || value == "")
            value = "- - - -";
        return { margins: value.split(' ').map(m => !parseInt(m) ? null : parseInt(m)) };
    }

    getRawValue(state = null){
        if(!state)
            state = this.state.margins;
        if(!Array.isArray(state)){
            if(typeof state.margins !== "undefined")
                state = state.margins;
            else
                return "- - - -";
        }
        if(!state)
            return "- - - -";
        return state.map(m => !m && m != 0 ? "-" : m).join(" ");
    }
    setStateByIndex(index, value){
        let newState = { margins: [...this.state.margins] };
        newState.margins[index] = value;
        this.setState(newState);
        return newState;
    }
    onChange(index){
        return (e) => {
            let val = e.target.value;
            let newState;
            if((!val || val == "") && val !== "0"){
                this.errors[index] = false;
                newState = this.setStateByIndex(index, null);
            }
            else{
                newState = this.setStateByIndex(index, val);
                this.errors[index] = val.match(/^-?\d+(\.\d+)?$/) == null;   
            }
            if(!this.errors.some(e => e))
                this.props.onChange(this.getRawValue(newState.margins));
            else
                this.ownRerender = true;
        }
    }

    getInput(i){
        return <>
            <span className="wa-descr">
                {i == 0 ? "Сверху" : (i == 1 ? "Справа" : (i == 2 ? "Снизу" : "Слева"))}
            </span>
            <input type="text"
                   value={this.state.margins[i] ?? ""} 
                   style={{width:"70px", ...(this.errors[i] ? {border:"1px solid red"} : {})}}
                   onChange={this.onChange(i)} />
        </>;
    }

	render() {
        if(!this.ownRerender){
            if(this.props.value != this.getRawValue())
                this.setState(this.extractState(this.props.value));
            this.errors.forEach((val, i, arr) => { arr[i] = false; });
        }
        else
            this.ownRerender = false;
        return (
            <div className="wa-margins">
                {this.getInput(3)}
                {this.getInput(1)}
                {this.getInput(0)}
                {this.getInput(2)}
            </div>
        );
    }
}
function InlineControl({title, children, style}){
    return (<div style={{...style, display:"flex", alignItems:"center"}}>
                <span className="wa-title" style={{marginRight:"10px"}}>{title}:</span>
                {children}
            </div>);
}
class WaWidgetsLineConfiguration extends Component {
	constructor(props) {
        super(props);
	}
	render() {
        let { config, onChange, type } = this.props;
        return !config ? <Info type="info" style={{maxWidth:"243px"}}>Не выбрана строка виджетов для конфигурирования внешнего вида</Info> : (
            <div className={classes("wa-widgets-line-configuration")}>
                <WaColorControl title="Цвет фона" style={{marginTop: "12px"}} value={config.bg} onChange={c => onChange({...config, bg: c})} />
                <div className="wa-title" style={{marginTop: "12px"}}>Внешние отступы:</div>
                <WaMarginsControl value={config.margin} onChange={v => onChange({...config, margin: v})} />
                <div className="wa-title" style={{marginTop: "12px"}}>Внутренние отступы:</div>
                <WaMarginsControl value={config.padding} onChange={v => onChange({...config, padding: v})} />
                {type == "header" || type == "footer" ?
                    <>
                        <InlineControl title="Высота" style={{marginTop: "17px"}}>
                            <WaSizeControl style={{width:"120px"}} value={config.height} onChange={(v) => onChange({...config, height: v})} />
                        </InlineControl>
                        <WaBorderControl title="Верхняя граница" style={{marginTop: "17px"}} value={config.topBorder} onChange={v => onChange({...config, topBorder: v})} />
                        <WaBorderControl title="Нижняя граница" style={{marginTop: "17px"}} value={config.bottomBorder} onChange={v => onChange({...config, bottomBorder: v})} />
                    </>
                    :
                    <InlineControl title="Ширина" style={{marginTop: "17px"}} >
                        <WaSizeControl style={{width:"120px"}} value={config.width} onChange={(v) => onChange({...config, width: v})} />
                    </InlineControl>
                }
            </div>
        );
	}
}

export default WaWidgetsLineConfiguration;