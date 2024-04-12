import { React, createRef } from "react";
import { Component } from "@wordpress/element";
import Info from "../../info";
import IconButton from "../../icon_button";
import "./style.scss";
import classes from "classnames";
import { DragDropContext, Draggable, Droppable } from "react-beautiful-dnd";

class WaSettingsProfileBarItem extends Component {
    constructor(props) {
        super(props);
    }
    render() {
        return (
            <Draggable
                draggableId={`bar-item-${this.props.config.key}`}
                index={this.props.index}
            >
                {(provided, snapshot) => (
                    <div
                        {...provided.draggableProps}
                        className={classes("wa-settings-profile-bar-item", {
                            active: this.props.active,
                            forbidden: this.props.forbidden,
                            moving: this.props.moving,
                        })}
                        onClick={(e) =>
                            !e.target.closest(
                                ".wa-settings-profile-bar-item-buttons"
                            ) && this.props.onEdit()
                        }
                        ref={provided.innerRef}
                    >
                        <IconButton
                            tooltip="Преместить"
                            type="move"
                            {...provided.dragHandleProps}
                            style={{
                                display:
                                    this.props.config.key == "common"
                                        ? "none"
                                        : "inline-block",
                            }}
                            inheritColor={true}
                        />
                        <span style={{ padding: "0px 7px 0px 5px" }}>
                            {this.props.config.profile}
                        </span>
                    </div>
                )}
            </Draggable>
        );
    }
}

class WaSettingsProfileBar extends Component {
    constructor(props) {
        super(props);
        this.state = {
            forbiddenedToMoveItem: null,
            movingItem: null,
            filter: "",
        };
        this.onDragStart = this.onDragStart.bind(this);
        this.onDragEnd = this.onDragEnd.bind(this);
        this.onDragUpdate = this.onDragUpdate.bind(this);
    }
    onDragEnd(result) {
        this.setState({
            ...this.state,
            forbiddenedToMoveItem: null,
            movingItem: null,
        });
        if (!result.destination) return;
        if (this.state.forbiddenedToMoveItem) return;
        let newConfig = [...this.props.config];
        let [item] = newConfig.splice(result.source.index, 1);
        newConfig.splice(result.destination.index, 0, item);
        this.props.onChange(newConfig);
    }
    onDragStart(result) {
        this.setState({
            ...this.state,
            movingItem: this.props.config[result.source.index].key,
        });
    }
    onDragUpdate(result) {
        let forbiddenedToMoveItem = !result.destination
            ? null
            : result.destination.index == this.props.config.length - 1
            ? this.state.movingItem
            : null;
        if (this.state.forbiddenedToMoveItem != forbiddenedToMoveItem) {
            this.setState({
                ...this.state,
                forbiddenedToMoveItem,
            });
        }
    }
    render() {
        let items = this.props.config.filter(
            (c) =>
                !this.state.filter ||
                c.profile
                    .toLowerCase()
                    .indexOf(this.state.filter.toLowerCase()) >= 0
        );
        return (
            <div className="wa-settings-profile-bar">
                <div className="header">
                    <input
                        type="text"
                        placeholder="Поиск"
                        style={{ width: "150px" }}
                        value={this.state.filter}
                        onChange={(e) =>
                            this.setState({
                                ...this.state,
                                filter: e.target.value,
                            })
                        }
                    />
                    <IconButton
                        className="rotate-content-45"
                        tooltip="Сбросить поиск"
                        type="add"
                        onClick={() =>
                            this.setState({ ...this.state, filter: "" })
                        }
                    />
                    <IconButton
                        tooltip="Добавить новый профиль"
                        type="add"
                        onClick={() => this.props.onCreate()}
                    />
                </div>
                <div className="priority-range-container">
                    <div className="priority-range-title">
                        <div className="priority-range-point"></div>
                        высокий приоритет
                    </div>
                    <DragDropContext
                        onDragEnd={this.onDragEnd}
                        onDragUpdate={this.onDragUpdate}
                        onDragStart={this.onDragStart}
                    >
                        <Droppable droppableId="sidebars-profile-bar-droppable">
                            {(provided, snapshot) => (
                                <div
                                    className={classes("list", {
                                        moving: this.state.movingItem,
                                    })}
                                    {...provided.droppableProps}
                                    ref={provided.innerRef}
                                >
                                    {items.map((c, i) => (
                                        <WaSettingsProfileBarItem
                                            key={c.key}
                                            index={i}
                                            forbidden={
                                                c.key ==
                                                this.state.forbiddenedToMoveItem
                                            }
                                            active={
                                                c.key ==
                                                this.props.editingProfile
                                            }
                                            moving={
                                                c.key == this.state.movingItem
                                            }
                                            config={c}
                                            onEdit={() =>
                                                this.props.onEdit(c.key)
                                            }
                                            onDelete={() =>
                                                this.props.onChange(
                                                    this.props.config.filter(
                                                        (_c) => _c.key != c.key
                                                    )
                                                )
                                            }
                                        />
                                    ))}
                                </div>
                            )}
                        </Droppable>
                    </DragDropContext>
                    <div className="priority-range-title">
                        <div className="priority-range-point"></div>
                        низкий приоритет
                    </div>
                </div>
                <Info
                    type={items.length > 0 ? "info" : "warning"}
                    style={{ width: "200px", padding: "7px 0px" }}
                >
                    {items.length > 0 ? (
                        <>
                            Профили, находящиеся <b>вверху </b>
                            списка, обладают более высоким приоритетом и
                            применяются первыми.
                        </>
                    ) : (
                        `Соответствующих поиску профилей не обнаружено`
                    )}
                </Info>
            </div>
        );
    }
}

export default WaSettingsProfileBar;
