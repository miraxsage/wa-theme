import { React, createRef } from "react";
import { Component } from "@wordpress/element";
import Info from "../../info";
import IconButton from "../../icon_button";
import "./style.scss";
import WaToggleButtonsList from "../../wa_toggle_buttons_list";
import ResourceChooser from "../wa_resource_chooser";
import WaToggleCheckbox from "../../wa_toggle_checkbox";

class WaResourceItem extends Component {
    constructor(props) {
        super(props);
    }
    render() {
        let { id, kind } = this.props;
        if (!kind) kind = "posts";
        if (kind.slice(-1) !== "s") kind = kind + "s";
        let data = window.wa.resources[kind].find((d) => d.id == id);
        let title = data.title ?? data.name;
        if (typeof title == "object") title = title.rendered;
        return (
            <div className="wa-resource-item">
                <span>
                    {title} ({data.id})
                </span>
                <IconButton
                    style={{ marginLeft: "5px" }}
                    tooltip="Удалить"
                    type="trash"
                    onClick={this.props.onDelete ?? (() => {})}
                />
            </div>
        );
    }
}

class WaResourcesListAccumulator extends Component {
    constructor(props) {
        super(props);
        this.config = this.props.config;
        this.kind = this.props.kind;
        if (this.kind.at(-1) != "s") this.kind = this.kind + "s";
        let resourcesLoaded = Boolean(window.wa?.resources);
        this.state = {
            resourcesLoaded,
            resourceChoosing: false,
            filter: "",
        };
        this.onResourcesLoaded = this.onResourcesLoaded.bind(this);
        this.chooseResource = this.chooseResource.bind(this);
        this.cancelChoosing = this.cancelChoosing.bind(this);
        this.onResourceChosen = this.onResourceChosen.bind(this);
    }
    onResourcesLoaded() {
        let newState = { ...this.state, resourcesLoaded: true };
        this.setState(newState);
    }
    componentWillUnmount() {
        document.removeEventListener(
            "WaResources_Loaded",
            this.onResourcesLoaded
        );
    }
    componentDidMount() {
        if (!this.state.resourcesLoaded)
            document.addEventListener(
                "WaResources_Loaded",
                this.onResourcesLoaded
            );
    }
    chooseResource(e) {
        e.preventDefault();
        this.setState({ ...this.state, resourceChoosing: true });
    }
    cancelChoosing(e) {
        e.preventDefault();
        this.setState({ ...this.state, resourceChoosing: false });
    }
    onResourceChosen(resource) {
        this.setState({ ...this.state, resourceChoosing: false });
        this.onChange(null, [
            ...this.config.ids.filter((id) => id != resource.id),
            resource.id,
        ]);
    }
    getModeFromList(list) {
        if (!list) return list;
        if (list.indexOf("include:1") >= 0) return "include";
        if (list.indexOf("exclude:1") >= 0) return "exclude";
        if (list.indexOf("none:1") >= 0) return "none";
        if (list.indexOf("0") >= 0) return "all";
        return list;
    }
    onChange(mode, ids) {
        mode = this.getModeFromList(mode) ?? this.config.mode;
        ids = ids ?? this.config.ids;
        this.props.onChange({ mode, ids });
    }
    render() {
        if (!this.state.resourcesLoaded) return "Загрузка...";

        let kind = this.kind;
        let { resourceChoosing } = this.state;
        let { mode } = this.props.config;

        const kindLabel = (forPosts, forPages, forArchives) =>
            kind.startsWith("post")
                ? forPosts
                : kind.startsWith("page")
                ? forPages
                : forArchives;
        const filterLabel = (forPosts, forPages, forCats, forTags) =>
            mode.endsWith("tags")
                ? forTags
                : mode.endsWith("cats") || kind.startsWith("archive")
                ? forCats
                : kind.startsWith("page")
                ? forPages
                : forPosts;
        const forKindLabel = kindLabel("записей", "страниц", "архивов");
        const addButtonLabel =
            "Добавить " + filterLabel("запись", "страницу", "категорию", "тег");
        const filterKind = filterLabel("posts", "pages", "cats", "tags");

        this.config = {
            ...this.props.config,
            ids: [
                ...this.props.config.ids.filter((id) =>
                    Boolean(
                        window.wa.resources[filterKind].find((r) => r.id == id)
                    )
                ),
            ],
        };
        let ids = !this.state.filter
            ? this.config.ids
            : this.config.ids.filter((id) => {
                  let res = window.wa.resources[filterKind].find(
                      (r) => r.id == id
                  );
                  let title = res.title ?? res.name;
                  if (typeof title == "object") title = title.rendered;
                  return (
                      res &&
                      `${title} (${res.id})`
                          .toLowerCase()
                          .indexOf(this.state.filter.toLowerCase()) >= 0
                  );
              });
        let readOnly = Boolean(this.props.readOnly);
        return (
            <>
                <div className="wa-resource-accumulator">
                    {!readOnly && (
                        <>
                            <WaToggleCheckbox
                                value={mode != "none"}
                                onChange={(id, checked) =>
                                    this.onChange(checked ? "all" : "none", [])
                                }
                                field={{ label: "Использовать" }}
                                withoutHeader={true}
                            />
                            {mode != "none" && (
                                <select
                                    value={mode}
                                    style={{ marginTop: "10px" }}
                                    onChange={(e) =>
                                        this.onChange(
                                            e.target.value,
                                            e.target.value.slice(-3) ==
                                                mode.slice(-3)
                                                ? null
                                                : []
                                        )
                                    }
                                >
                                    <option value="all">
                                        Для всех {forKindLabel}
                                    </option>
                                    {kind != "archives" && (
                                        <>
                                            <option value="include">
                                                Для некоторых {forKindLabel}
                                            </option>
                                            <option value="exclude">
                                                Для всех {forKindLabel}, кроме
                                                выбранных
                                            </option>
                                        </>
                                    )}
                                    {kind != "pages" && (
                                        <>
                                            <option value="include_cats">
                                                Для всех {forKindLabel} с
                                                категориями
                                            </option>
                                            <option value="exclude_cats">
                                                Для всех {forKindLabel} без
                                                категорий
                                            </option>
                                            <option value="include_tags">
                                                Для всех {forKindLabel} с тегами
                                            </option>
                                            <option value="exclude_tags">
                                                Для всех {forKindLabel} без
                                                тегов
                                            </option>
                                        </>
                                    )}
                                </select>
                            )}
                        </>
                    )}
                    <Info
                        style={{
                            padding: "4px 0px 9px 0px",
                            marginTop: "10px",
                        }}
                    >
                        {mode == "all"
                            ? "Данный профиль будет применяться на " +
                              kindLabel(
                                  "страницах всех записей",
                                  "всех страницах",
                                  "страницах любых архивов"
                              )
                            : mode == "include"
                            ? "Данный профиль будет применяться только на " +
                              kindLabel(
                                  "страницах выбранных ниже записей",
                                  "выбранных ниже страницах",
                                  "страницах архивов выбранных ниже категорий"
                              )
                            : mode == "exclude"
                            ? "Данный профиль будет применяться на всех страницах, кроме " +
                              kindLabel(
                                  "выбранных ниже записей",
                                  "выбранных ниже",
                                  "архивов с выбранными ниже категорями"
                              )
                            : mode == "include_cats"
                            ? "Данный профиль будет применяться для всех " +
                              forKindLabel +
                              ", относящихся к выбранным ниже категориям"
                            : mode == "exclude_cats"
                            ? "Данный профиль будет применяться на всех " +
                              forKindLabel +
                              ", не относящихся к выбранным ниже категориям"
                            : mode == "include_tags"
                            ? "Данный профиль будет применяться для всех " +
                              forKindLabel +
                              ", содержащих выбранные ниже теги"
                            : mode == "exlude_tags"
                            ? "Данный профиль будет применяться для всех " +
                              forKindLabel +
                              ", не содержащих выбранные ниже теги"
                            : "Данный профиль не будет применяться ни на каких " +
                              kindLabel(
                                  "страницах записей",
                                  "страницах",
                                  "страницах архивов"
                              )}
                    </Info>
                    {mode != "all" && mode != "none" ? (
                        <>
                            <div className="header">
                                <button
                                    className="button"
                                    style={{ marginRight: "1px" }}
                                    onClick={this.chooseResource}
                                >
                                    {addButtonLabel}
                                </button>
                                <input
                                    type="text"
                                    value={this.state.filter}
                                    onChange={(e) =>
                                        this.setState({
                                            ...this.state,
                                            filter: e.target.value,
                                        })
                                    }
                                    placeholder="Поиск в добавленных"
                                />
                                <IconButton
                                    className="rotate-content-45"
                                    tooltip="Сбросить поиск"
                                    type="add"
                                    onClick={() =>
                                        this.setState({
                                            ...this.state,
                                            filter: "",
                                        })
                                    }
                                />
                            </div>
                            <div
                                className="list"
                                style={{
                                    display: "flex",
                                    flexWrap: "wrap",
                                    gap: "5px",
                                }}
                            >
                                {ids.length > 0 ? (
                                    ids.map((id) => (
                                        <WaResourceItem
                                            kind={filterKind}
                                            id={id}
                                            onDelete={() =>
                                                this.onChange(
                                                    mode,
                                                    ids.filter(
                                                        (_id) => _id != id
                                                    )
                                                )
                                            }
                                        />
                                    ))
                                ) : (
                                    <Info
                                        type={
                                            this.state.filter
                                                ? "warning"
                                                : "info"
                                        }
                                        style={
                                            this.state.filter
                                                ? { padding: "7px 9px" }
                                                : { padding: "4px 0px 9px 0px" }
                                        }
                                    >
                                        {!this.state.filter ? (
                                            <>
                                                Не выбрано
                                                {filterLabel(
                                                    " ни одной записи ",
                                                    " ни одной страницы ",
                                                    " ни одной категории ",
                                                    " ни одного тега "
                                                )}
                                                для фильтрации
                                            </>
                                        ) : (
                                            <>
                                                Соответствующих поиску
                                                {filterLabel(
                                                    " записей ",
                                                    " страниц ",
                                                    " категорий ",
                                                    " тегов "
                                                )}
                                                не обнаружено
                                            </>
                                        )}
                                    </Info>
                                )}
                            </div>
                        </>
                    ) : null}
                </div>
                {resourceChoosing && (
                    <div className="resource-chooser-overlap">
                        <div
                            style={{
                                display: "flex",
                                flexDirection: "row",
                                gap: "7px",
                                alignItems: "center",
                            }}
                        >
                            <IconButton
                                tooltip="Назад"
                                tooltipPos="right"
                                type="back-arrow"
                                onClick={this.cancelChoosing}
                            />
                            <div
                                className="wa-title"
                                style={{ marginTop: "1px" }}
                            >{`Выберите соответствующую ${kindLabel(
                                "запись",
                                "страницу",
                                "категорию"
                            )} из списка для ее добавления в фильтр:`}</div>
                        </div>
                        <ResourceChooser
                            kind={filterLabel(
                                "post",
                                "page",
                                kind.startsWith("archive")
                                    ? "archive_cat"
                                    : "cat",
                                "tag"
                            )}
                            onChosen={this.onResourceChosen}
                        />
                        <button
                            className="button button-outline"
                            onClick={this.cancelChoosing}
                            style={{ justifySelf: "right" }}
                        >
                            Отмена
                        </button>
                    </div>
                )}
            </>
        );
    }
}

export default WaResourcesListAccumulator;
