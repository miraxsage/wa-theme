import { React } from "react";
import { Component } from "@wordpress/element";
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";
import classes from "classnames";
import WaWidgetsLineConfiguration from "./wa_widgets_line_configuration";
import WaWidgetsLine from "./wa_widgets_line";
import IconButton from "../icon_button";
import Info from "../info";
import WaToggleCheckbox from "../wa_toggle_checkbox";
import { generateId } from "../services";

function generateNewEmptyLine() {
    return {
        key: generateId(),
        width: null,
        height: null,
        margin: "- - - -",
        padding: "- - - -",
        bg: null,
        topBorder: null,
        bottomBorder: null,
        items: [],
    };
}

class WaFrameConfiguration extends Component {
    constructor(props) {
        super(props);
        this.state = { adjustingLineConfig: null, activeDraggingType: null };
        if (this.props.name != "header" && this.props.name != "footer")
            this.state.adjustingLineConfig = this.props.config.lines[0];
        this.onDragEnd = this.onDragEnd.bind(this);
        this.onDragStart = this.onDragStart.bind(this);
    }
    onDragStart(e) {
        this.setState({ ...this.state, activeDraggingType: e.type });
    }
    onDragEnd(result) {
        this.setState({ ...this.state, activeDraggingType: null });
        if (!result.destination) return;
        if (result.type == "lines") {
            let newConfig = { ...this.props.config };
            newConfig.lines = [...newConfig.lines];
            let [line] = newConfig.lines.splice(result.source.index, 1);
            newConfig.lines.splice(result.destination.index, 0, line);
            this.props.onChange(newConfig);
        } else {
            let newConfig = { ...this.props.config };
            newConfig.lines = [...newConfig.lines];
            let lineFromIndex = newConfig.lines.findIndex(
                (ln) => ln.key == result.source.droppableId
            );
            let lineFrom = (newConfig.lines[lineFromIndex] = {
                ...newConfig.lines[lineFromIndex],
            });
            let [item] = lineFrom.items.splice(result.source.index, 1);
            let lineTo = lineFrom;
            if (result.source.droppableId !== result.destination.droppableId) {
                let lineToIndex = newConfig.lines.findIndex(
                    (ln) => ln.key == result.destination.droppableId
                );
                lineTo = newConfig.lines[lineToIndex] = {
                    ...newConfig.lines[lineToIndex],
                };
            }
            lineTo.items.splice(result.destination.index, 0, item);
            this.props.onChange(newConfig);
        }
    }
    render() {
        let { config, name, onChange: onChangeInitial } = this.props;
        if (
            this.state.adjustingLineConfig &&
            !config.lines.find((l) => l == this.state.adjustingLineConfig)
        ) {
            this.setState({ ...this.state, adjustingLineConfig: null });
            return;
        }
        let onChangeLineConfig = (line) => {
            let newConfig = {
                ...config,
                lines: config.lines.map((l) =>
                    l == this.state.adjustingLineConfig ? line : l
                ),
            };
            this.setState({ ...this.state, adjustingLineConfig: line });
            onChangeInitial(newConfig);
        };
        let onChangeLineItems = (line) => {
            return (newLine) => {
                if (this.state.adjustingLineConfig == line)
                    this.setState({
                        ...this.state,
                        adjustingLineConfig: newLine,
                    });
                let newConfig = {
                    ...config,
                    lines: config.lines.map((l) => (l == line ? newLine : l)),
                };
                onChangeInitial(newConfig);
            };
        };
        let onLineDeletion = (line, justClear = false) => {
            return () => {
                let newLine = justClear ? generateNewEmptyLine() : null;
                if (this.state.adjustingLineConfig == line)
                    this.setState({
                        ...this.state,
                        adjustingLineConfig: newLine,
                    });
                let newConfig = {
                    ...config,
                    lines: justClear
                        ? config.lines.map((l) => (l == line ? newLine : l))
                        : config.lines.filter((l) => l != line),
                };
                onChangeInitial(newConfig);
            };
        };
        let onLineAdjusting = (config) => {
            this.setState({ ...this.state, adjustingLineConfig: config });
        };
        return (
            <div className={classes("wa-frame-configuration")}>
                <div>
                    <WaToggleCheckbox
                        value={config.visible}
                        style={{ marginBottom: "5px" }}
                        withoutHeader={true}
                        field={{
                            label:
                                (name == "header"
                                    ? "Header"
                                    : name == "footer"
                                    ? "Footer"
                                    : "Сайдбар") + " активен",
                            description: "",
                        }}
                        onChange={() =>
                            onChangeInitial({
                                ...config,
                                visible: !config.visible,
                            })
                        }
                    />
                    <div className="wa-separator"></div>
                    <div className="wa-title">
                        {name == "header" || name == "footer"
                            ? "Настройки строки виджетов:"
                            : "Настройки колонки виджетов:"}
                    </div>
                    <WaWidgetsLineConfiguration
                        type={name}
                        config={this.state.adjustingLineConfig}
                        onChange={onChangeLineConfig}
                    />
                </div>
                {name == "header" || name == "footer" ? (
                    <div style={{ "overflow-x": "auto", "max-width": "100%" }}>
                        <div style={{ width: "max-content" }}>
                            {config.lines && config.lines.length > 0 ? (
                                <DragDropContext
                                    onDragEnd={this.onDragEnd}
                                    onDragStart={this.onDragStart}
                                >
                                    <Droppable droppableId="lines" type="lines">
                                        {(provided, snapshot) => (
                                            <div
                                                className={classes(
                                                    "lines-container",
                                                    {
                                                        moving: this.state
                                                            .activeDraggingType,
                                                    }
                                                )}
                                                {...provided.droppableProps}
                                                ref={provided.innerRef}
                                            >
                                                {config.lines.map((c, i) => (
                                                    <WaWidgetsLine
                                                        config={c}
                                                        key={c.key}
                                                        index={i}
                                                        activeDraggingType={
                                                            this.state
                                                                .activeDraggingType
                                                        }
                                                        orientation="horizontal"
                                                        adjusting={
                                                            c ==
                                                            this.state
                                                                .adjustingLineConfig
                                                        }
                                                        onAdjusting={
                                                            onLineAdjusting
                                                        }
                                                        onChange={onChangeLineItems(
                                                            c
                                                        )}
                                                        onDelete={onLineDeletion(
                                                            c
                                                        )}
                                                    />
                                                ))}
                                            </div>
                                        )}
                                    </Droppable>
                                </DragDropContext>
                            ) : (
                                <Info
                                    style={{
                                        paddingLeft: "42px",
                                        paddingTop: "27px",
                                    }}
                                >
                                    Еще не добавлено ни
                                    <br />
                                    одной строки виджетов
                                    <br />
                                    <a
                                        href="#"
                                        onClick={(e) => {
                                            e.preventDefault();
                                            onChangeInitial({
                                                ...config,
                                                lines: [
                                                    ...config.lines,
                                                    generateNewEmptyLine(),
                                                ],
                                            });
                                        }}
                                    >
                                        Добавить первую
                                    </a>
                                </Info>
                            )}
                            {config.lines && config.lines.length > 0 && (
                                <IconButton
                                    type="add"
                                    style={{
                                        opacity:
                                            this.state.activeDraggingType ==
                                            null
                                                ? 1
                                                : 0,
                                        marginTop: "5px",
                                    }}
                                    className="wa-widgets-line-add-button--horizontal"
                                    onClick={() =>
                                        onChangeInitial({
                                            ...config,
                                            lines: [
                                                ...config.lines,
                                                generateNewEmptyLine(),
                                            ],
                                        })
                                    }
                                    tooltip="Добавить новую строку виджетов"
                                />
                            )}
                        </div>
                    </div>
                ) : (
                    <DragDropContext
                        onDragEnd={this.onDragEnd}
                        onDragStart={this.onDragStart}
                    >
                        <WaWidgetsLine
                            config={config.lines[0]}
                            activeDraggingType={this.state.activeDraggingType}
                            onChange={onChangeLineItems(config.lines[0])}
                            onDelete={onLineDeletion(config.lines[0], true)}
                        />
                    </DragDropContext>
                )}
            </div>
        );
    }
}

export default WaFrameConfiguration;
