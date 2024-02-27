import { React } from "react";
import { Component } from '@wordpress/element';
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";
import classes from "classnames";
import IconButton from "../icon_button";
import ReactModal from "react-modal";
import WaWidgetEditor from "./wa_widget_editor";

function Widget({widget}){
    return <div className="wa-widget-element">
             <div className="wa-widget-header">{widget.title}</div>
             <div className="wa-widget-desciption">{widget.descr}</div>
           </div>;
}

class WaWidgetSelector extends Component {

	constructor(props) {
        super(props);
        this.state = { modalIsShown: false, menuWidth: document.getElementById("adminmenuwrap").getBoundingClientRect().width };
        this.onDelete = this.onDelete.bind(this);
        this.onSave = this.onSave.bind(this);
        this.modal = this.modal.bind(this);
        this.onModalSizeChanged = this.onModalSizeChanged.bind(this);
	}

    onModalSizeChanged(){
        let menuW = document.getElementById("adminmenuwrap").getBoundingClientRect().width;
        if(menuW != this.state.menuWidth)
            this.setState({...this.state, menuWidth: menuW});
    }

    componentDidMount(){
        window.addEventListener("resize", this.onModalSizeChanged);
    }

    componentWillUnmount(){
        window.removeEventListener("resize", this.onModalSizeChanged);
    }

    onDelete(e){
        e.preventDefault();
        if(this.props.onDelete)
            this.props.onDelete(this.props.config);
    }

    modal(show){
        return () => this.setState({...this.state, modalIsShown: show});
    }

    onSave(config){
        if(this.props.onChange)
            this.props.onChange(config);
        this.setState({...this.state, modalIsShown: false});
    }

	render() {
        let { config, index } = this.props;
        let menuW = this.state.menuWidth;
        return (
            <Draggable draggableId={config.key} index={index}>
                {(provided, snapshot) => {
                    return <div ref={provided.innerRef} {...provided.draggableProps} >
                        <ReactModal className="wa-modal" ariaHideApp={false} 
                                    style={{
                                        overlay: { zIndex:1, display: "grid", justifyItems: "center", alignItems: "center" },
                                        content: { inset: "unset", maxHeight: "55%", position: "absolute", left: "50%", maxWidth: "1000px", 
                                                width: `calc(100% - ${menuW + 100}px)`, transform: `translateX(calc(${menuW / 2}px - 50%))`,  
                                                height: `calc(100% - 214px)`, maxHeight: "1000px" }}} 
                                    overlayRef={(node) => {
                                        if(node){
                                            node.addEventListener('click', (e) => {
                                                if(!e.target.closest(".ReactModal__Content") && e.target.closest(".ReactModal__Overlay"))
                                                    this.modal(false)();
                                            });
                                        }
                                    }}
                                    isOpen={this.state.modalIsShown}>
                            <div className="wa-modal-title">
                                Настройте виджет
                                <button type="button" className="wa-modal-close" onClick={this.modal(false)}>
                                    <span class="dashicons dashicons-plus-alt2"></span>
                                </button>
                            </div>
                            <WaWidgetEditor widget={config} onSave={this.onSave} />
                        </ReactModal>
                        <div style={this.props.style} className={classes("wa-widget-selector", 
                                                                 this.props.orientation, 
                                                                 {"empty": !config?.title},
                                                                 {"opt-btns-hidden": this.props.activeDraggingType != null},
                                                                 {"dragging": snapshot.isDragging})}>
                            {!config?.title ?
                                <div className="centered">
                                    <IconButton type="edit" tooltip="Создать виджет" onClick={this.modal(true)} />
                                </div> 
                                : 
                                <Widget widget={config} />
                            }
                            <div className="wa-optional-buttons-panel" >
                                {config?.title ? <IconButton type="edit" tooltip="Изменить виджет" tooltipPos="left" onClick={this.modal(true)} /> : null}
                                <IconButton type="trash" tooltip="Удалить виджет" tooltipPos="left" onClick={this.onDelete} />
                                <IconButton type="move" tooltip="Переместить виджет" tooltipPos="left" {...provided.dragHandleProps} />
                            </div>
                        </div>
                    </div>;}}
            </Draggable>
        );
	}
}

export default WaWidgetSelector;