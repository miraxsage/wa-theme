import { React } from "react";
import { Component } from "@wordpress/element";
import classes from "classnames";
import "./style.scss";

class WaOptionsButton extends Component {
    constructor(props) {
        super(props);
        this.state = { opened: false };
    }

    render() {
        let state = this.state;
        let { onReset, onTabReset, onFullReset, context } = this.props;
        return (
            <button
                className={classes("wa-options-button", {
                    "wa-options-button--opened": state.opened,
                    "wa-options-button--hidden": this.props.hide,
                })}
                onMouseEnter={() => this.setState({ ...state, opened: true })}
                onMouseLeave={() => this.setState({ ...state, opened: false })}
                onClick={(e) => e.preventDefault()}
                style={{ ...this.props.style }}
            >
                <span class="dashicons dashicons-ellipsis"></span>
                <ul className="wa-options-button__items">
                    <li className="wa-options-button__item" onClick={onReset}>
                        <span className="dashicons dashicons-exit"></span>
                        <span className="wa-options-button__item-text">Сбросить настройку к общим</span>
                    </li>
                    {onTabReset && (
                        <li className="wa-options-button__item" onClick={onTabReset}>
                            <span className="dashicons dashicons-arrow-left-alt"></span>
                            <span className="wa-options-button__item-text">Сбросить все настройки вкладки</span>
                        </li>
                    )}
                    {onFullReset && (
                        <li className="wa-options-button__item" onClick={onFullReset}>
                            <span className="dashicons dashicons-undo"></span>
                            <span className="wa-options-button__item-text">
                                Сбросить все настройки {context === "term_meta" ? "блока" : "страницы"}
                            </span>
                        </li>
                    )}
                </ul>
            </button>
        );
    }
}

export default WaOptionsButton;
