import React from "react";
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";
import WaResetOptionsHeader from "../wa_reset_options_header";
import WaPersonalizedComponent from "../wa_personalized_component";
import classes from "classnames";
import "./style.scss";

class WaAdvancedPillList extends WaPersonalizedComponent {
    constructor(props) {
        super(props);
        this.state.items = [];
        this.label = props.field.label ?? "";
        this.description = props.field.description ?? "";
        let choices = props.field.reordered_choices;
        for (let key of Object.keys(choices)) {
            let val = choices[key];
            let label = val;
            let sortable = props.field.sortable;
            let classes = "";
            let checked = false;
            let metaitem = false;
            if (typeof val == "object") {
                label = val.label ?? "";
                sortable = val.sortable ?? sortable;
                classes = val.classes ?? val.class ?? classes;
                checked = val.checked ?? checked;
                metaitem = classes.match(/(^| )metaitem( |$)/i) != null;
            }
            this.state.items.push({
                key,
                label,
                sortable,
                classes,
                checked,
                metaitem,
                status: null,
            });
        }
        this.state.items = this.getReorderItemsByValue(this.props.value);
        this.changeVisibility = this.changeVisibility.bind(this);
        this.onDragEnd = this.onDragEnd.bind(this);
        this.onDragUpdate = this.onDragUpdate.bind(this);
        this.onChange = this.onChange.bind(this);
    }

    onChange() {
        const { id, onChange } = this.props;
        let val = "";
        for (let item of this.state.items) {
            val += (val ? "," : "") + item.key + ":" + (item.checked ? "1" : "0");
        }
        if (val != this.props.value) onChange(id, val);
    }

    getReorderItemsByValue(value) {
        let pairs = value.split(",");
        pairs = pairs.map((p) => {
            let kv = p.split(":");
            return { key: kv[0], val: kv[1] == true };
        });
        let newItems = [];
        pairs.forEach((p) => {
            let item = this.state.items.find((i) => i.key == p.key);
            if (item) {
                item.checked = p.val;
                newItems.push(item);
            }
        });
        return newItems;
    }

    changeVisibility(item_index) {
        if (this.state.items[item_index].metaitem) return;
        let newItems = [...this.state.items];
        if (!this.nonPersonalizedMoment())
            newItems[item_index] = {
                ...newItems[item_index],
                checked: !newItems[item_index].checked,
            };
        this.setPersonalized();
        this.setState({ items: newItems });
    }

    onPersonalizedChanged(personalized, commonValue) {
        if (!personalized) {
            this.setState({ items: this.getReorderItemsByValue(commonValue) });
        }
    }

    onDragEnd(result) {
        if (!result.destination) return;
        let item = this.state.items[result.source.index];
        if (!this.checkItemPosValidity(item, result.source.index, result.destination.index, false)) return;
        let newItems = [...this.state.items];
        let [removed] = newItems.splice(result.source.index, 1);
        newItems.splice(result.destination.index, 0, removed);
        this.setPersonalized();
        this.setState({ items: newItems });
    }

    onDragUpdate(result) {
        if (!result.destination) return;
        let item = this.state.items[result.source.index];
        this.checkItemPosValidity(item, result.source.index, result.destination.index);
    }

    checkItemPosValidity(item, initpos, pos, setStatus = true) {
        if (item.key.indexOf("similar_records") >= 0) {
            item.status = null;
            let header_open = this.state.items.reduce((prev, curr, index) =>
                curr.key.indexOf("header_open") >= 0 ? index : prev
            );
            let header_close = this.state.items.reduce((prev, curr, index) =>
                curr.key.indexOf("header_close") >= 0 ? index : prev
            );
            if (
                (pos > header_open && pos < header_close) ||
                (initpos < header_open && pos == header_open) ||
                (initpos > header_close && pos == header_close)
            ) {
                if (setStatus) item.status = "forbidden";
                return false;
            }
        }
        return true;
    }

    onReset() {}

    render() {
        this.onChange();
        const { id, name, value, field } = this.props;
        const content = (
            <>
                <input
                    type="hidden"
                    id={this.props.id}
                    name={this.props.name}
                    value={this.getValueToSave(this.props.value)}
                />
                <DragDropContext onDragEnd={this.onDragEnd} onDragUpdate={this.onDragUpdate}>
                    <Droppable droppableId="droppable">
                        {(provided, snapshot) => (
                            <div {...provided.droppableProps} ref={provided.innerRef}>
                                {this.state.items.map((item, index) => (
                                    <Draggable key={item.key} draggableId={item.key} index={index}>
                                        {(provided, snapshot) => {
                                            return (
                                                <div
                                                    className={[
                                                        "wa-advanced-pill-list__item",
                                                        item.checked ? "wa-advanced-pill-list__item_checked" : "",
                                                        item.status == "forbidden" ? " blocked" : "",
                                                        ...item.classes
                                                            .replace(",", " ")
                                                            .replace(/\s+/, " ")
                                                            .split(" "),
                                                    ].join(" ")}
                                                    ref={provided.innerRef}
                                                    {...provided.draggableProps}
                                                    {...(item.metaitem ? {} : provided.dragHandleProps)}
                                                    onClick={() => this.changeVisibility(index)}
                                                >
                                                    {item.metaitem ? (
                                                        <div
                                                            style={{
                                                                display: "none",
                                                            }}
                                                            {...provided.dragHandleProps}
                                                        ></div>
                                                    ) : (
                                                        ""
                                                    )}
                                                    <span
                                                        class={
                                                            "dashicons" +
                                                            (item.checked
                                                                ? " dashicons-visibility"
                                                                : " dashicons-hidden")
                                                        }
                                                        style={{
                                                            display: item.metaitem ? "none" : "inline-block",
                                                        }}
                                                    ></span>
                                                    <span>{item.label}</span>
                                                </div>
                                            );
                                        }}
                                    </Draggable>
                                ))}
                                {provided.placeholder}
                            </div>
                        )}
                    </Droppable>
                </DragDropContext>
            </>
        );
        return field.label ? (
            <div ref={this.rootRef} className={classes("wa-advanced-pill-list", this.getPersonalizedClass())}>
                <WaResetOptionsHeader label={field.label} {...this.resetProps} context={field.context}>
                    {this.description ? (
                        <span className="wa-advanced-pill-list__description">{this.description}</span>
                    ) : (
                        ""
                    )}
                    {content}
                </WaResetOptionsHeader>
            </div>
        ) : (
            content
        );
    }
}

export default WaAdvancedPillList;
