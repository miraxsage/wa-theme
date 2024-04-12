import IconButton from "../../icon_button";
import { capitalize, generateId } from "../../services";
import WaAutoComplete from "../../wa_autocomplete";
import { Component } from "@wordpress/element";

function itemLabel(itemKind, { variant } = {}) {
    const label =
        itemKind == "pages"
            ? variant == "of"
                ? "страниц"
                : "страницы"
            : itemKind == "archives"
            ? variant == "of"
                ? "архивов"
                : "архивы"
            : itemKind == "posts"
            ? variant == "of"
                ? "записей"
                : "записи"
            : itemKind == "cats"
            ? "категории"
            : "метки";
    return label;
}

function inclusionKindData({ kind, defaultSelected, withoutAllInclustion }) {
    return [
        ...(withoutAllInclustion
            ? []
            : [
                  {
                      id: "all",
                      name: "Включить все " + itemLabel(kind),
                      iconClass: "dashicons dashicons-image-filter icon-size-12 ml2 mr4",
                      defaultSelected: defaultSelected == "all",
                  },
              ]),
        {
            id: "include",
            name: "Включить только",
            iconClass: "dashicons dashicons-plus-alt2 icon-size-18",
            defaultSelected: defaultSelected == "include",
        },
        {
            id: "exclude",
            name: "Исключить все, кроме",
            iconClass: "dashicons dashicons-minus icon-size-18",
            defaultSelected: defaultSelected == "exclude",
        },
    ];
}

function inclusionLabel(inclusionKind, forInclude, forExclude) {
    return inclusionKind == "include" ? forInclude : forExclude;
}

function filterKindDataForPosts({ inclusionKind: kind, filterKind: filter }) {
    return [
        {
            id: "items",
            name: inclusionLabel(kind, "Выбранные записи", "Выбранных записей"),
            defaultSelected: filter == "items",
        },
        {
            id: "cats",
            name: inclusionLabel(kind, "Записи", "Записей") + " с категориями",
            defaultSelected: filter == "cats",
        },
        {
            id: "tags",
            name: inclusionLabel(kind, "Записи", "Записей") + " с метками",
            defaultSelected: filter == "tags",
        },
    ];
}
function filterKindDataForPages({ inclusionKind: kind, filterKind: filter }) {
    return [
        {
            id: "items",
            name: inclusionLabel(kind, "Выбранные страницы", "Выбранных страниц"),
            defaultSelected: filter == "items",
        },
        {
            id: "children",
            name: inclusionLabel(kind, "Дочерние страницы", "Дочерних страниц"),
            defaultSelected: filter == "children",
        },
    ];
}
function filterKindDataForArchives({ inclusionKind: kind, filterKind: filter }) {
    return [
        {
            id: "items",
            name: inclusionLabel(kind, "Выбранные архивы", "Выбранных архивов"),
            defaultSelected: filter == "items",
        },
        {
            id: "cats",
            name: inclusionLabel(kind, "Архивы", "Архивов") + " с категориями",
            defaultSelected: filter == "cats",
        },
        {
            id: "tags",
            name: inclusionLabel(kind, "Архивы", "Архивов") + " с метками",
            defaultSelected: filter == "tags",
        },
    ];
}
const archivesItems = [
    {
        id: "main_page",
        name: "Главная страница",
    },
    {
        id: "search",
        name: "Страница поиска",
    },
];

function filterKindData(itemKind, params) {
    if (itemKind == "archives") return filterKindDataForArchives(params);
    if (itemKind == "pages") return filterKindDataForPages(params);
    return filterKindDataForPosts(params);
}

function filterItemsData(itemKind, filterKind, filterItems) {
    let resKind = null;
    if (itemKind == "pages") resKind = "cats";
    else if (itemKind == "posts") resKind = filterKind == "items" ? "posts" : filterKind;
    else {
        if (filterKind == "items") return archivesItems;
        resKind = filterKind;
    }
    return window.wa.resources[resKind].map((r) => ({
        id: r.id,
        name: r.name ?? (typeof r.title == "object" ? r.title?.rendered : r.title),
        description: "id: " + r.id + (r.description ? ", " : "") + r.description,
        defaultSelected: filterItems.includes(r.id),
    }));
}

/* type WaFilterConditionProps = { 
    kind: "posts" | "pages" | "archives", 
    withoutAllInclustion: boolean,
    onChange,
    onDelete
}
*/
export default class WaFilterCondition extends Component {
    constructor(props) {
        super();
        this.state = this.extractPropsState(props);
        this.refreshByState = true;
    }
    extractPropsState(props) {
        const filter = (this.props ?? props)?.filter ?? {};
        const mode = filter.mode ?? "all";
        const inclusionKind = mode.startsWith("include") ? "include" : mode.startsWith("exclude") ? "exclude" : "all";
        const filterKind =
            inclusionKind == "all"
                ? ""
                : mode.endsWith("cats")
                ? "cats"
                : mode.endsWith("tags")
                ? "tags"
                : mode.endsWith("children") && this.props.kind == "pages"
                ? "children"
                : "items";
        return {
            inclusionKind,
            filterKind: filterKind,
            filterItems: filterKind ? filter.ids ?? [] : [],
        };
    }
    refresh(newState) {
        if (this.props.onChange && newState.inclusionKind && (newState.filterKind || newState.inclusionKind == "all")) {
            this.props.onChange({
                id: this.props.filter?.id ?? generateId(),
                mode:
                    newState.inclusionKind +
                    (newState.filterKind && newState.filterKind != "items" ? "_" + newState.filterKind : ""),
                ids: newState.filterItems ?? [],
            });
            this.refreshByState = false;
        } else {
            this.refreshByState = true;
            this.setState(newState);
        }
    }
    render() {
        const { kind, withoutAllInclustion } = this.props;
        const { inclusionKind, filterKind, filterItems } = !this.refreshByState ? this.extractPropsState() : this.state;
        return (
            <div className="wa-filter-condition" style={this.props.style ?? {}}>
                <WaAutoComplete
                    pholder="Включить / исключить"
                    data={inclusionKindData({
                        kind,
                        defaultSelected: inclusionKind,
                        withoutAllInclustion,
                    })}
                    onSelected={(item) => {
                        const newInclusionKind = item?.id ?? "";
                        const newState = {
                            inclusionKind: newInclusionKind,
                            filterKind: newInclusionKind && newInclusionKind != "all" ? filterKind : "",
                            filterItems: newInclusionKind && newInclusionKind != "all" ? filterItems : [],
                        };
                        this.refresh(newState);
                    }}
                />
                {inclusionKind && inclusionKind != "all" && (
                    <WaAutoComplete
                        pholder={
                            capitalize(
                                itemLabel(kind, {
                                    variant: inclusionKind == "exclude" ? "of" : "",
                                })
                            ) + "..."
                        }
                        data={filterKindData(kind, {
                            inclusionKind,
                            filterKind,
                        })}
                        onSelected={(item) =>
                            this.refresh({
                                inclusionKind,
                                filterKind: item?.id ?? "",
                                filterItems: [],
                            })
                        }
                    />
                )}
                {filterKind && (
                    <WaAutoComplete
                        style={{ flexGrow: 1 }}
                        pholder={capitalize(itemLabel(filterKind == "items" ? kind : filterKind)) + "..."}
                        multiple={true}
                        data={filterItemsData(kind, filterKind, filterItems)}
                        onSelected={(items) => {
                            this.refresh({
                                inclusionKind,
                                filterKind,
                                filterItems: items ? items.map((i) => i.id) : [],
                            });
                        }}
                    />
                )}
                <IconButton
                    className="rotate-content-45 flex-aligned-center"
                    style={{ height: "32px" }}
                    tooltip="Удалить условие"
                    type="add"
                    onClick={() => this.props.onDelete?.()}
                />
            </div>
        );
    }
}
