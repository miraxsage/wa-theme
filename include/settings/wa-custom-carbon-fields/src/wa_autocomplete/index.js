import React, { createRef, useState } from "react";
import { Component } from "@wordpress/element";
import classes from "classnames";
import "./style.scss";
import { generateId, getTextWidth } from "../services";

/* type WaAutoCompleteProps = { 
    multiple: boolean, 
    data: { id:string, name:string, descr:string, iconClass: string },
    className,
    style, 
    onChange,
    onSelected,
} */
export default class WaAutoComplete extends Component {
    constructor(props) {
        super();
        this.rootRef = createRef();
        this.id = "wa-autocomplete-" + generateId();
        document.addEventListener("mousedown", (e) => (this.lastClickTarget = e.target));
        this.renderedData = JSON.stringify(props.data);
        this.state = {
            suggestions: props.data,
            areSuggsShown: false,
            selectedIds: props.data.filter((d) => d.defaultSelected).map((d) => d.id),
            inputVal: props.multiple ? "" : props.data.find((d) => d.defaultSelected)?.name ?? "",
            invalid: false,
        };
        this.onChange = this.onChange.bind(this);
        this.onSelected = this.onSelected.bind(this);
        this.suggsVisibility = this.suggsVisibility.bind(this);
    }
    suggsVisibility(show) {
        const { inputVal, selectedIds } = this.state;
        const { multiple } = this.props;
        if (!show && inputVal) {
            const inputItem = this.props.data.find((d) => d.name == inputVal);
            this.setState({
                areSuggsShown: false,
                selectedIds: inputItem ? [...selectedIds, inputItem.id] : selectedIds,
                inputVal: multiple ? (inputItem ? "" : inputVal) : inputVal,
                invalid: !inputItem,
            });
        } else if (this.state.areSuggsShown != show)
            this.setState({
                areSuggsShown: show,
                invalid: false,
            });
    }
    onChange(e) {
        const val = typeof e == "string" ? e : e?.target?.value;
        const newSelectedIds = this.props.multiple
            ? this.state.selectedIds
            : this.props.data.filter((d) => val && d.name == val).map((d) => d.id);
        this.setState({
            areSuggsShown: true,
            inputVal: val ?? "",
            suggestions:
                !val || (!this.props.multiple && this.props.data.find((d) => d.name == val))
                    ? this.props.data
                    : this.props.data.filter((d) => d.name.startsWith(e.target.value)),
            selectedIds: newSelectedIds,
        });
        if (this.props.onChange) this.props.onChange(val);
        if (!this.props.multiple && this.props.onSelected) {
            const addedId = newSelectedIds.some((id) => !this.state.selectedIds.includes(id));
            this.props.onSelected(addedId ? this.data.find((d) => d.id == addedId) : null);
        }
    }
    onSelected(id) {
        if (id == "none") return;
        const selectedItem = this.props.data.find((d) => d.id == id);
        const isDeselect = this.props.multiple && this.state.selectedIds.includes(id);
        const saveFocus = this.props.multiple && this.state.areSuggsShown;
        const newSelectedIds = this.props.multiple
            ? isDeselect
                ? this.state.selectedIds.filter((s) => s != id)
                : [...this.state.selectedIds, id]
            : [id];
        this.setState({
            selectedIds: newSelectedIds,
            inputVal: this.props.multiple ? "" : selectedItem.name,
            suggestions: this.props.data,
            areSuggsShown: saveFocus,
        });
        setTimeout(() => this.rootRef.current.querySelector("input")[saveFocus ? "focus" : "blur"]());
        if (this.props.onSelected)
            this.props.onSelected(
                this.props.multiple
                    ? this.props.data.filter((d) => newSelectedIds.includes(d.id))
                    : this.props.data.find((d) => d.id == id)
            );
    }
    componentDidUpdate() {
        const newRenderedData = JSON.stringify(this.props.data);
        if (this.state.selectedIds.some((id) => this.props.data.every((d) => d.id != id))) {
            this.setState({
                selectedIds: [],
                inputVal: "",
                suggestions: this.props.data,
                invalid: false,
            });
            this.renderedData = newRenderedData;
            return;
        }
        if (this.renderedData != newRenderedData) {
            let newSuggestions = this.props.data,
                newInputVal = this.state.inputVal,
                newInvalid = this.state.invalid,
                newSelectedIds = this.state.selectedIds;
            if (this.state.inputVal) {
                const inputItem = this.props.multiple
                    ? this.props.data.find((d) => d.name == this.state.inputVal)
                    : this.state.selectedIds?.length > 0
                    ? this.props.data.find((d) => d.id == this.state.selectedIds[0])
                    : this.props.data.find((d) => d.name == this.state.inputVal);
                if (this.props.multiple) {
                    if (!inputItem) {
                        newSuggestions = this.props.data.filter((d) => d.name == this.state.inputVal);
                    } else {
                        if (!this.rootRef.querySelector("input").matches(":focus")) {
                            newInputVal = "";
                            if (!newSelectedIds.includes(inputItem.id))
                                newSelectedIds = [...newSelectedIds, inputItem.id];
                            newInvalid = false;
                        }
                    }
                } else {
                    if (inputItem) {
                        newInputVal = inputItem.name;
                        if (!newSelectedIds.includes(inputItem.id)) newSelectedIds = [inputItem.id];
                    } else {
                        newSelectedIds = [];
                        newInvalid = !this.rootRef.querySelector("input").matches(":focus");
                    }
                }
            }
            this.setState({
                suggestions: newSuggestions,
                selectedIds: newSelectedIds,
                inputVal: newInputVal,
                invalid: newInvalid,
            });
            this.renderedData = newRenderedData;
            return;
        }
        const inputHeight = this.rootRef.current
            .querySelector(".wa-autocomplete-control")
            .getBoundingClientRect().height;
        if (inputHeight && inputHeight != this.state.inputHeight) this.setState({ inputHeight });
    }
    render() {
        const { pholder, multiple = false, className, style } = this.props;
        const { inputVal, suggestions, areSuggsShown, selectedIds, inputHeight } = this.state;
        let selectedVal = null;
        if (!multiple && selectedIds.length > 0) selectedVal = this.props.data.find((d) => d.id == selectedIds[0]);
        let inputWidth = "auto";
        if (!this.rootRef.current) this.setState({ ...this.state });
        else {
            inputWidth =
                getTextWidth(inputVal ? inputVal : pholder, this.rootRef.current.querySelector("input")) + 22 + "px";
        }
        return (
            <div
                className={classes("wa-autocomplete", {
                    "wa-autocomplete-focused": areSuggsShown,
                    className,
                })}
                style={style}
                id={this.id}
                ref={this.rootRef}
            >
                <div
                    className={classes("wa-autocomplete-control", {
                        "wa-autocomplete-control_invalid": this.state.invalid,
                        "wa-autocomplete-control_multiple": this.props.multiple,
                    })}
                >
                    {multiple
                        ? selectedIds.map((id) => {
                              const item = this.props.data.find((d) => d.id == id);
                              if (!item) return;
                              return (
                                  <span className={classes("wa-autocomplete-tag")} key={item.name}>
                                      {item.iconClass && <span className={item.iconClass}></span>}
                                      {item.name}
                                      <span
                                          className="dashicons dashicons-plus-alt2"
                                          onClick={() => this.onSelected(id)}
                                      ></span>
                                  </span>
                              );
                          })
                        : selectedVal?.iconClass && <span className={selectedVal.iconClass}></span>}
                    <input
                        placeholder={pholder}
                        type="search"
                        value={inputVal}
                        style={{ width: inputWidth }}
                        onChange={this.onChange}
                        onFocus={(e) => {
                            if (!e.target.value) this.onChange();
                            else this.suggsVisibility(true);
                        }}
                        onBlur={(e) => {
                            if (!this.lastClickTarget.closest("#" + this.id)) this.suggsVisibility(false);
                        }}
                        onKeyUp={(e) => {
                            if (e.code == "Escape" || e.code == "Enter") e.target.blur();
                            if (e.code == "Backspace")
                                this.setState({
                                    selectedIds: selectedIds.slice(0, -1),
                                });
                        }}
                    />
                    <div
                        className="input-empty-tail"
                        onClick={() => this.rootRef.current?.querySelector("input")?.focus()}
                    ></div>
                </div>
                <div
                    className="wa-autocomplete-suggestions"
                    style={{
                        display: !areSuggsShown ? "none" : "block",
                        top: inputHeight - 1 + "px",
                    }}
                >
                    {(suggestions.length > 0
                        ? suggestions
                        : [
                              {
                                  id: "none",
                                  name: "Нет совпадений",
                                  iconClass: "dashicons dashicons-info-outline icon-size-16",
                              },
                          ]
                    ).map((sugg) => (
                        <div
                            onClick={() => this.onSelected(sugg.id)}
                            onMouseDown={(e) => e.preventDefault()}
                            key={sugg.id}
                            className={classes("wa-suggestion-item", {
                                active: selectedIds.includes(sugg.id),
                                none: sugg.id == "none",
                            })}
                        >
                            <div
                                className={classes("wa-suggestion-item-title", {
                                    "wa-suggestion-item-title-bold": !!sugg.descr,
                                })}
                            >
                                {sugg.iconClass && <span className={sugg.iconClass}></span>}
                                {sugg.name}
                            </div>
                            {sugg.descr && (
                                <div className="wa-suggestion-item-descr">
                                    {"id: " + sugg.id + (sugg.descr ? ", " : "") + sugg.descr}
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            </div>
        );
    }
}
