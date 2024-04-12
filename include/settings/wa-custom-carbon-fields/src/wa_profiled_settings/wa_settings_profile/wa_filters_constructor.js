import { React, createRef } from "react";
import { Component } from "@wordpress/element";
import Info from "../../info";
import IconButton from "../../icon_button";
import "./style.scss";
import WaToggleCheckbox from "../../wa_toggle_checkbox";
import WaFilterCondition from "./wa_filter_condition";
import { generateId } from "../../services";
import { DragDropContext, Draggable, Droppable } from "react-beautiful-dnd";

class WaFiltersConstructor extends Component {
    constructor(props) {
        super(props);
        this.config = this.props.config;
        this.kind = this.props.kind;
        if (this.kind.at(-1) != "s") this.kind = this.kind + "s";
        let resourcesLoaded = Boolean(window.wa?.resources);
        this.state = {
            resourcesLoaded,
        };
        this.onResourcesLoaded = this.onResourcesLoaded.bind(this);
        this.onDragEnd = this.onDragEnd.bind(this);
    }
    onResourcesLoaded() {
        let newState = { ...this.state, resourcesLoaded: true };
        this.setState(newState);
    }
    componentWillUnmount() {
        document.removeEventListener("WaResources_Loaded", this.onResourcesLoaded);
    }
    componentDidMount() {
        if (!this.state.resourcesLoaded) document.addEventListener("WaResources_Loaded", this.onResourcesLoaded);
    }
    filterResourceLabel(filterMode, forPosts, forPages, forCats, forTags) {
        return filterMode.endsWith("tags")
            ? forTags
            : filterMode.endsWith("cats")
            ? forCats
            : this.kind == "pages"
            ? forPages
            : forPosts;
    }
    filterResourceKind(filterMode) {
        return this.filterResourceLabel(filterMode, "posts", "pages", "cats", "tags");
    }
    kindLabel(forPosts, forPages, forArchives) {
        return this.kind.startsWith("post") ? forPosts : this.kind.startsWith("page") ? forPages : forArchives;
    }
    onDragEnd(result) {
        if (!result.destination || !this.props.onChange) return;
        let newFilters = [...this.filters];
        let [item] = newFilters.splice(result.source.index, 1);
        newFilters.splice(result.destination.index, 0, item);
        this.props.onChange(newFilters);
    }
    render() {
        if (!this.state.resourcesLoaded) return "Загрузка...";
        let filters = this.props.config;
        if (filters)
            filters = filters.map((f) => {
                const filterResourceKind = this.filterResourceKind(f.mode);
                return {
                    ...f,
                    ids: f.ids.filter((id) => Boolean(window.wa.resources[filterResourceKind].find((r) => r.id == id))),
                };
            });
        this.filters = filters;
        let readOnly = Boolean(this.props.readOnly);
        const filtersControls = filters && (
            <DragDropContext onDragEnd={this.onDragEnd}>
                <Droppable droppableId="filters-droppable">
                    {(provided, snapshot) => (
                        <div {...provided.droppableProps} ref={provided.innerRef}>
                            {filters.map((f, i) => (
                                <Draggable
                                    draggableId={`filter-condition-${f.id}`}
                                    key={`filter-condition-${f.id}`}
                                    index={i}
                                >
                                    {(provided, snapshot) => {
                                        return (
                                            <div {...provided.draggableProps} ref={provided.innerRef}>
                                                <div
                                                    style={{
                                                        display: "flex",
                                                        gap: "5px",
                                                        marginTop: filters.length > 1 ? "0px" : "3px",
                                                        padding: "3px 0px 3px 0px",
                                                    }}
                                                >
                                                    <IconButton
                                                        tooltip="Преместить условие"
                                                        type="move"
                                                        {...provided.dragHandleProps}
                                                        style={{
                                                            height: "32px",
                                                            display: filters?.length > 1 ? "flex" : "none",
                                                        }}
                                                        className="flex-aligned-center"
                                                    />

                                                    <WaFilterCondition
                                                        key={f.id}
                                                        kind={this.kind}
                                                        filter={f}
                                                        style={{
                                                            flexGrow: 1,
                                                        }}
                                                        withoutAllInclustion={filters.length > 1}
                                                        onChange={(newf) =>
                                                            this.props.onChange?.(
                                                                filters.map((oldf) => (oldf.id == f.id ? newf : oldf))
                                                            )
                                                        }
                                                        onDelete={() =>
                                                            this.props.onChange?.(
                                                                filters.filter((delf) => f.id != delf.id)
                                                            )
                                                        }
                                                    />
                                                </div>
                                            </div>
                                        );
                                    }}
                                </Draggable>
                            ))}
                        </div>
                    )}
                </Droppable>
            </DragDropContext>
        );
        return (
            <>
                <div className="wa-filters-constructor">
                    <Info
                        style={{
                            padding: "4px 0px 9px 0px",
                        }}
                    >
                        {readOnly
                            ? "Данный профиль будет применяться на " +
                              this.kindLabel("страницах всех записей", "всех страницах", "страницах любых архивов") +
                              " по умолчанию, если выше не определено других, подходящих для " +
                              this.kindLabel("конкретной записи", "конкретной страницы", "конкретного архива")
                            : filters
                            ? "Добавьте условие: какие " +
                              this.kindLabel("записи", "страницы", "архивы") +
                              " будут включать в себя дальнейшие настройки"
                            : "Активируйте настройки текущего профиля для " +
                              this.kindLabel("записей", "страниц", "архивов") +
                              ' нажатием кнопки "Использовать"'}
                    </Info>
                    {!readOnly && (
                        <>
                            <WaToggleCheckbox
                                value={!!filters}
                                onChange={(id, checked) =>
                                    this.props.onChange(
                                        checked
                                            ? [
                                                  {
                                                      id: generateId(),
                                                      mode: "all",
                                                      ids: [],
                                                  },
                                              ]
                                            : null
                                    )
                                }
                                field={{ label: "Использовать" }}
                                withoutHeader={true}
                            />
                            {!!filters && (filters.length > 1 || filters[0].mode != "all") && (
                                <button
                                    type="button"
                                    className="button regular-icon-button"
                                    style={{ margin: "6px 0px 0px 0px" }}
                                    onClick={() => {
                                        this.props.onChange?.([
                                            {
                                                id: generateId(),
                                                mode: "include",
                                                ids: [],
                                            },
                                            ...filters,
                                        ]);
                                    }}
                                >
                                    <span
                                        class="dashicons dashicons-plus-alt2"
                                        style={{
                                            position: "relative",
                                            top: "1px",
                                        }}
                                    ></span>
                                    Добавить условие
                                </button>
                            )}
                            {filters &&
                                (filters.length > 1 ? (
                                    <div className="priority-range-container" style={{ marginTop: "2px" }}>
                                        <div className="priority-range-title" style={{ marginBottom: "3px" }}>
                                            <div className="priority-range-point" style={{ top: "8px" }}></div>
                                            высокий приоритет
                                        </div>
                                        {filtersControls}
                                        <div className="priority-range-title">
                                            <div className="priority-range-point"></div>
                                            низкий приоритет
                                        </div>
                                    </div>
                                ) : (
                                    filtersControls
                                ))}
                        </>
                    )}
                </div>
            </>
        );
    }
}

export default WaFiltersConstructor;
