import { React, createRef } from "react";
import { Component } from "@wordpress/element";
import Info from "../../info/index.js";
import IconButton from "../../icon_button/index.js";
import ReactModal from "react-modal";
import "./style.scss";
import WaSettingsProfile from "./wa_settings_profile.js";

class WaSettingsProfileSelector extends Component {
    constructor(props) {
        super(props);
        this.modalRef = null;
        this.action = createRef();
        this.state = {
            modalIsShown: false,
            modalLeft: 0,
            modalWidth: 0,
            menuWidth: 160,
        };
        this.onModalSizeChanged = this.onModalSizeChanged.bind(this);
        this.createNewProfile = this.createNewProfile.bind(this);
        this.modal = this.modal.bind(this);
    }

    onModalSizeChanged() {
        let menuW = document.getElementById("adminmenuwrap").getBoundingClientRect().width;
        let modalW = this.modalRef?.getBoundingClientRect().width ?? 500;
        let winW = document.body.clientWidth;
        let modalL = (winW - menuW) / 2 - modalW / 2 + menuW;
        if (modalL != this.state.modalLeft || modalW != this.state.modalWidth)
            this.setState({
                ...this.state,
                modalLeft: modalL,
                modalWidth: modalW,
                menuWidth: menuW,
            });
    }

    componentDidMount() {
        window.addEventListener("resize", this.onModalSizeChanged);
    }

    componentWillUnmount() {
        window.removeEventListener("resize", this.onModalSizeChanged);
    }

    componentDidUpdate() {
        this.onModalSizeChanged();
        if (typeof this.props.action == "function") {
            this.props.action((action, params) => {
                this.actionParams = params;
                if (action == "openProfile" && !this.state.modalIsShown) this.modal(true)();
            });
        } else delete this.actionParams;
    }

    modal(show) {
        return () => {
            this.setState({ ...this.state, modalIsShown: show });
        };
    }

    createNewProfile(e) {
        e?.preventDefault?.();
        this.action.current = (action) => {
            action("createNewProfile");
            this.action.current = null;
        };
        this.modal(true)();
    }

    render() {
        return (
            <>
                <ReactModal
                    className="wa-modal"
                    ariaHideApp={false}
                    style={{
                        overlay: {
                            zIndex: 1,
                            display: "grid",
                            justifyItems: "center",
                            alignItems: "center",
                        },
                        content: {
                            inset: "unset",
                            maxHeight: "55%",
                            position: "absolute",
                            left: this.state.modalLeft + "px",
                            maxWidth: "1000px",
                            width: `calc(100% - ${this.state.menuWidth + 100}px)`,
                            height: `calc(100% - 214px)`,
                            maxHeight: "1000px",
                        },
                    }}
                    overlayRef={(node) => {
                        if (node) {
                            node.addEventListener("click", (e) => {
                                if (!e.target.closest(".ReactModal__Content")) this.modal(false)();
                            });
                        }
                    }}
                    contentRef={(node) => {
                        this.modalRef = node;
                        this.onModalSizeChanged();
                    }}
                    isOpen={this.state.modalIsShown}
                >
                    <div className="wa-modal-title">
                        Выберите профиль для редактирования
                        <button type="button" className="wa-modal-close" onClick={this.modal(false)}>
                            <span class="dashicons dashicons-plus-alt2"></span>
                        </button>
                    </div>
                    <WaSettingsProfile
                        selectedTab={this.actionParams?.tab}
                        config={this.props.config}
                        chosenProfile={this.props.chosenProfile}
                        onChange={(newConfig) => {
                            this.props.onChange(newConfig);
                            if (newConfig.length == 1) this.setState({ ...this.state, modalIsShown: false });
                        }}
                        onSelect={(key) => {
                            this.modal(false)();
                            this.props.onSelect(key);
                        }}
                        action={this.action.current}
                    />
                </ReactModal>
                <div className="wa-sidebars-profile-selector-container">
                    <span className="title">Профиль:</span>
                    <div className="value">
                        {this.props.config.find((c) => c.key == this.props.chosenProfile)?.profile}
                    </div>
                    {this.props.config && this.props.config.length > 1 && (
                        <button type="button" className="button regular-icon-button" onClick={this.modal(true)}>
                            <span
                                class="dashicons dashicons-admin-generic"
                                style={{
                                    position: "relative",
                                    top: "0.5px",
                                    marginRight: "2px",
                                }}
                            ></span>
                            Настройка / Переключение
                        </button>
                    )}
                    <button type="button" className="button regular-icon-button" onClick={this.createNewProfile}>
                        <span
                            class="dashicons dashicons-plus-alt2"
                            style={{
                                position: "relative",
                                inset: "1px 0px 0px -1px",
                            }}
                        ></span>
                        Добавить новый
                    </button>
                    <Info
                        style={{
                            padding: "3px 0px 16px 0px",
                            marginTop: "10px",
                            width: "auto",
                        }}
                    >
                        {this.props.chosenProfile == "common" && (
                            <>
                                Настройки "общего" профиля применяются ко всем разделам по умолчанию. <br />
                                Для выборочных и более тонких настроек{" "}
                                <a
                                    href="admin.php?page=wa-profiled-settings.php&action=create-profile"
                                    onClick={this.createNewProfile}
                                >
                                    создайте
                                </a>{" "}
                                дополнительный профиль поверх "общего"
                                <div style={{ height: "5px" }}></div>
                            </>
                        )}
                        Различные профили позволяют указывать различные настройки сайдбаров, последовательности блоков,
                        schema-атрибутов и пр. для выбранных условий (отдельных страниц, категорий и т.д.)
                    </Info>
                </div>
            </>
        );
    }
}

export default WaSettingsProfileSelector;
