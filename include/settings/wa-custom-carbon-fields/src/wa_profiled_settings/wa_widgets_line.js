import { React, createRef } from "react";
import { Component } from '@wordpress/element';
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";
import classes from "classnames";
import WaWidgetSelector from "./wa_widget_selector";
import IconButton from "../icon_button";
import Info from "../info";
import { generateId } from "../services";

class WaWidgetsLine extends Component {

	constructor(props) {
        super(props);
        this.btnsContainerRef = createRef();
        this.state = { hovered: false }
        this.onAdd = this.onAdd.bind(this);
        this.onDelete = this.onDelete.bind(this);
        this.onAdjusting = this.onAdjusting.bind(this);
        this.hover = this.hover.bind(this);
	}

    hover(hover){
        if(hover && this.props.activeDraggingType != null)
            return;
        return (function(){
            this.setState({hovered: hover});
        }).bind(this);
    }

    onAdd(){
        this.props.onChange({...this.props.config, items: [...this.props.config.items, { key: generateId() } ]});
    }

    onDelete(){
        // confirmation question should be here
        if(this.props.onDelete)
            this.props.onDelete(this.props.config);
    }

    onAdjusting(){
        if(this.props.onAdjusting)
            this.props.onAdjusting(this.props.adjusting ? null : this.props.config);
    }

    componentDidUpdate(){
        let isHover = this.btnsContainerRef.current.closest(".wa-widgets-line").matches(':hover') && !this.props.activeDraggingType != null;
        if(isHover != this.state.hovered)
            this.setState({hovered: isHover});
    }

	render() {
        let { orientation = "vertical", config, adjusting, index, activeDraggingType } = this.props;
        let vert = orientation == "vertical";
        let horiz = orientation == "horizontal";
        let line_buttons_active = /*config.items.length == 0 || */vert || (this.state.hovered && activeDraggingType == null) ? 1 : 0;
        let line_settings_active = line_buttons_active > 0 || adjusting ? 1 : 0;
        return (
            <Draggable draggableId={config.key} index={index}>
            {(provided, snapshot) => (
                <div ref={provided.innerRef} 
                        {...provided.draggableProps}
                        className={classes("wa-widgets-line", 
                                        orientation, 
                                        {
                                            dragging: snapshot.isDragging, 
                                            "dragging-sibling" : activeDraggingType == "lines" && !snapshot.isDragging
                                        })}  
                        onMouseEnter={this.hover(true)} 
                        onMouseLeave={this.hover(false)}
                >
                    {config.items.length == 0 && vert ? <Info style={{width: "auto", margin: "0 auto", padding: "10px 12px"}} type="info">Еще не добавлено<br/>ни одного виджета<br/><a href="#" className="wa-link" onClick={(e) => {e.preventDefault(); this.onAdd();}}>Добавить первый</a></Info> : null}
                    <div ref={this.btnsContainerRef} className={classes("wa-buttons-container", {"vertical": horiz, "horizontal": vert})}
                        style={{ marginLeft: vert ? "auto" : "5px" }}>
                        {!horiz && config.items.length > 0 && <IconButton tooltip="Добавить виджет" className="wa-widgets-line-add-button--horizontal" style={{visibility: snapshot.isDraggingOver || activeDraggingType != null ? "collapse" : "visible"}} tooltipPos={horiz ? "left" : "top"} type="add" onClick={this.onAdd} />}
                        {horiz && <IconButton tooltip={horiz ? "Удалить строку" : "Очистить строку"} tooltipPos={horiz ? "right" : "top"} type="trash" onClick={this.onDelete} style={{marginTop: horiz ? "1px" : 0, marginLeft: vert ? "3px" : 0, opacity:line_buttons_active}} />}
                        {config.items.length > 0 && horiz ? <IconButton active={adjusting} tooltip="Настроить внешний вид строки" tooltipPos={horiz ? "right" : "top"} type="settings" onClick={this.onAdjusting} style={{marginTop: horiz ? "1px" : 0, marginLeft: vert ? "3px" : 0, opacity:line_settings_active}} /> : null}
                        {<IconButton tooltip="Переместить строку" tooltipPos="right" type="move" {...provided.dragHandleProps} style={{marginTop: horiz ? "1px" : 0, opacity:line_buttons_active, display: vert ? "none" : "block"}} />}
                    </div>
                    <Droppable droppableId={config.key} type="widgets" direction={orientation}>
                        {(provided, snapshot) => (
                            <div className="wa-widgets-line-content-container">
                                <div style={{
                                        display: horiz ? "flex" : "block",
                                        marginRight: horiz ? "0px" : "5px", 
                                        marginTop: horiz ? "0px" : "5px",
                                        paddingLeft: horiz ? "0px" : "0px",
                                        paddingRight: horiz ? "5px" : "0px",
                                        borderWidth: !snapshot.isDraggingOver && activeDraggingType == null ? "1px" : (horiz ? "1px 0px 1px 1px" : "1px 1px 0px 1px"), 
                                        borderStyle: activeDraggingType != null && !snapshot.isDraggingOver ? "dashed" : "solid",
                                        borderColor: snapshot.isDraggingOver ? "#2271b1" : (activeDraggingType == "lines" ? "white" : (!horiz && activeDraggingType == null ? "white" : "#e2e4e7")), 
                                        background: !snapshot.isDraggingOver ? "white" : `linear-gradient(${horiz ? '90deg' : '180deg'}, #bdd8ed, transparent)`}}
                                    {...provided.droppableProps} 
                                    className="wa-widgets-line-content"
                                    ref={provided.innerRef}
                                >
                                    {config.items.length > 0 ?
                                        config.items.map((c, i) => 
                                            <WaWidgetSelector config={c} 
                                                index={i}
                                                activeDraggingType={activeDraggingType}
                                                key={c.key}
                                                orientation={orientation}
                                                style={{marginLeft: "5px", marginTop: horiz ? "5px" : i == 0 && activeDraggingType == null ? "0px" : "5px" }}
                                                onChange={(newConfig) => this.props.onChange({...config, items: config.items.map(e => e == c ? newConfig : e)})}
                                                onDelete={() => this.props.onChange({...config, items: config.items.filter(e => e != c)})} />)
                                        :
                                        horiz ? <Info style={{width: "auto", margin: "0 auto"}}>Еще не добавлено<br/>ни одного виджета<br/><a href="" className="wa-link" onClick={(e) => {e.preventDefault(); this.onAdd();}}>Добавить первый</a></Info> : null
                                    }
                                </div>
                                {horiz && config.items.length > 0 && <IconButton tooltip="Добавить виджет" 
                                                        className="wa-widgets-line-add-button" 
                                                        style={{visibility: snapshot.isDraggingOver || activeDraggingType != null ? "collapse" : "visible"}} 
                                                        tooltipPos={horiz ? "left" : "top"} 
                                                        type="add" 
                                                        onClick={this.onAdd} />}
                            </div>
                        )}
                    </Droppable>
                </div>
            )}
            </Draggable>
        );
	}
}

export default WaWidgetsLine;