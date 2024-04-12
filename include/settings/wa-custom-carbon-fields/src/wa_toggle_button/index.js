import { React } from "react";
import { Component } from "@wordpress/element";
import "./style.scss";
import classes from "classnames";
import { generateId } from "../services";

class WaToggleButton extends Component {
    constructor(props) {
        super(props);
        this.state = { checked: props.checked };
        this.id = props.id ?? generateId();
    }

    render() {
        const { name, checked, label } = this.props;
        return (
            <span
                class={classes("wa-toggle-button", {
                    "wa-toggle-button--checked": checked,
                    "wa-toggle-button--owned-by-list": this.props.ownedByList,
                    "wa-toggle-button--first": this.props.firstInList,
                    "wa-toggle-button--last": this.props.lastInList,
                    "wa-toggle-button--with-gutter":
                        checked && this.props.hasGutter,
                })}
            >
                <input
                    type="checkbox"
                    className="wa-toggle-button__checkbox"
                    onChange={(e) =>
                        this.props.onChange(this.id, e.target.checked)
                    }
                    checked={checked}
                    id={this.id}
                    name={name}
                />
                <label className="wa-toggle-button__label" for={this.id}>
                    <span>{label}</span>
                    {this.props.ownedByList && !this.props.lastInList ? (
                        <div className="wa-toggle-button__gutter"></div>
                    ) : (
                        ""
                    )}
                </label>
            </span>
        );
    }
}

export default WaToggleButton;
