import { React, createRef } from "react";
import { Component } from '@wordpress/element';
import Info from "../../info";
import IconButton from "../../icon_button";
import ReactModal from "react-modal";
import "./style.scss";
import WaSidebarsProfile from "./wa_sidebars_profile.js";

class WaSidebarsProfileSelector extends Component { 

	constructor(props) {
        super(props);
        this.modalRef = null;
        this.state = { modalIsShown: false, modalLeft: 0, modalWidth: 0, menuWidth: 160 };
        this.onModalSizeChanged = this.onModalSizeChanged.bind(this);
        this.modal = this.modal.bind(this);
	}

    onModalSizeChanged(){
        let menuW = document.getElementById("adminmenuwrap").getBoundingClientRect().width;
        let modalW = this.modalRef?.getBoundingClientRect().width ?? 500;
        let winW = document.body.clientWidth;
        let modalL = ((winW - menuW) / 2) - (modalW / 2) + menuW;
        if(modalL != this.state.modalLeft || modalW != this.state.modalWidth)
            this.setState({...this.state, modalLeft: modalL, modalWidth: modalW, menuWidth: menuW});
    }

    componentDidMount(){
        window.addEventListener("resize", this.onModalSizeChanged);
    }

    componentWillUnmount(){
        window.removeEventListener("resize", this.onModalSizeChanged);
    }

    componentDidUpdate(){
        this.onModalSizeChanged();
    }

    modal(show){
        return () => this.setState({...this.state, modalIsShown: show});
    }

	render() {
        return (
            <>
                <ReactModal className="wa-modal" ariaHideApp={false} 
                            style={{
                                overlay: { zIndex:1, display: "grid", justifyItems: "center", alignItems: "center" },
                                content: { inset: "unset", maxHeight: "55%", position: "absolute", left: this.state.modalLeft + "px", maxWidth: "1000px", 
                                        width: `calc(100% - ${this.state.menuWidth + 100}px)`,  
                                        height: `calc(100% - 214px)`, maxHeight: "1000px" }}} 
                            overlayRef={(node) => {
                                if(node){
                                    node.addEventListener('click', (e) => {
                                        if(!e.target.closest(".ReactModal__Content"))
                                            this.modal(false)();
                                    });
                                }
                            }}
                            contentRef={node => {
                                this.modalRef = node;
                                this.onModalSizeChanged();
                            }}
                            isOpen={this.state.modalIsShown}>
                    <div className="wa-modal-title">
                        Выберите (настройте) профиль
                        <button type="button" className="wa-modal-close" onClick={this.modal(false)}>
                            <span class="dashicons dashicons-plus-alt2"></span>
                        </button>
                    </div>
                    <WaSidebarsProfile 
                        config={this.props.config}
                        chosenProfile={this.props.chosenProfile}
                        onChange={this.props.onChange}
                        onSelect={(key) => {
                            this.modal(false)();
                            this.props.onSelect(key);
                        }}
                    />
                </ReactModal>
                <div className="wa-sidebars-profile-selector-container">
                    <span className="title">Профиль:</span>
                    <div className="value">{this.props.config.find(c => c.key == this.props.chosenProfile)?.profile}</div>
                    <IconButton onClick={this.modal(true)} tooltip="Выбрать другой" tooltipPos="top" type="settings" style={{margin: "0px 7px 0px 7px", position: "relative", bottom: "2px"}} />
                    <Info style={{padding: "3px 0px 16px 0px", width: "auto"}}>Различные профили позволяют указывать различные настройки сайдбаров для выбранных условий (отдельных страниц, категорий и т.д.)</Info>
                </div>
            </>
        );
	}
}

export default WaSidebarsProfileSelector;