import { React, createRef } from "react";
import { Component } from "@wordpress/element";
import Info from "../../info/index.js";
import IconButton from "../../icon_button/index.js";
import "./style.scss";
import WaSettingsProfileBar from "./wa_settings_profiles_bar.js";
import WaTabs from "../../wa_tabs/index.js";
import WaResourcesListAccumulator from "./wa_resources_list_accumulator.js";

class WaSettingsProfile extends Component {
    effectQueue = [];
    constructor(props) {
        super(props);
        this.state = {
            editingProfile: this.props.chosenProfile,
            deleteQuery: false,
        };
        this.onCreateProfile = this.onCreateProfile.bind(this);
        this.onChangeProfile = this.onChangeProfile.bind(this);
        this.onChange = this.onChange.bind(this);
        this.onProfileDelete = this.onProfileDelete.bind(this);
        this.onProfileDeleteQueryOutsideClick =
            this.onProfileDeleteQueryOutsideClick.bind(this);
        this.onChangeEditingProfile = this.onChangeEditingProfile.bind(this);
        this.resourcesAccumulator = this.resourcesAccumulator.bind(this);
    }
    async loadResources() {
        let load = async (link) => {
            let res = await fetch(link);
            return res.json();
        };
        let postsPromise = load(
            "/wp-json/wp/v2/posts?_fields[]=title&_fields[]=id&_fields[]=modified&_fields[]=slug"
        );
        let pagesPromise = load(
            "/wp-json/wp/v2/pages?_fields[]=title&_fields[]=id&_fields[]=modified&_fields[]=slug"
        );
        let catsPromise = load(
            "/wp-json/wp/v2/categories?_fields[]=name&_fields[]=id&_fields[]=description&_fields[]=slug"
        );
        let [posts, pages, cats] = await Promise.all([
            postsPromise,
            pagesPromise,
            catsPromise,
        ]);
        window.wa.resources = {
            posts,
            pages: [
                {
                    id: "home",
                    title: { rendered: "Главная страница" },
                    slug: "home",
                    modified: "-",
                },
                ...pages,
            ],
            cats: [
                {
                    id: "search",
                    name: "Результаты поиска",
                    slug: "search",
                    description: "Архив записей с результатами поиска",
                },
                ...cats,
            ],
        };
        const event = new CustomEvent(
            "WaResources_Loaded",
            window.wa.resources
        );
        document.dispatchEvent(event);
    }
    editingProfile() {
        return this.props.config.find(
            (c) => c.key == this.state.editingProfile
        );
    }
    componentDidMount() {
        if (!window.wa) window.wa = {};
        if (!window.wa.resources) this.loadResources();
    }
    componentDidUpdate() {
        if (this.props.action && typeof this.props.action == "function")
            this.props.action((action) => {
                if (action == "createNewProfile") this.onCreateProfile();
            });
    }
    onCreateProfile() {
        let subObj = structuredClone(wa_common__profiled_settings_default);
        let key = Math.floor(Math.random() * Date.now()).toString(16);
        this.setState({ ...this.state, editingProfile: key });
        this.props.onChange([
            { ...subObj, profile: "Новый профиль", key: key },
            ...this.props.config,
        ]);
    }
    onChangeProfile(name, filter) {
        this.props.onChange(
            this.props.config.map((c) =>
                c.key == this.state.editingProfile
                    ? {
                          ...c,
                          profile: name ?? c.profile,
                          filter: filter ?? c.filter,
                      }
                    : c
            )
        );
    }
    onChangeEditingProfile(key) {
        this.setState({ ...this.state, editingProfile: key });
    }
    onChange(newConfig) {
        if (!newConfig.find((c) => c.key == this.state.editingProfile)) {
            let editingIndex = this.props.config.findIndex(
                (c) => c.key == this.state.editingProfile
            );
            if (editingIndex > 0) editingIndex--;
            else editingIndex++;
            this.setState({
                ...this.state,
                editingProfile: this.props.config[editingIndex].key,
            });
        }
        this.props.onChange(newConfig);
    }
    onProfileDelete(forced = false) {
        if (forced)
            this.onChange(
                this.props.config.filter(
                    (_c) => _c.key != this.state.editingProfile
                )
            );
        this.setState({ deleteQuery: !forced });
    }
    onProfileDeleteQueryOutsideClick(e) {
        if (!e.target.closest(".wa-sidebars-profile--delete-query-container"))
            this.setState({ deleteQuery: false });
    }
    resourcesAccumulator(kind) {
        return (
            <WaResourcesListAccumulator
                kind={kind}
                readOnly={this.editingProfile().key == "common"}
                config={this.editingProfile().filter[kind]}
                onChange={(newConfig) =>
                    this.onChangeProfile(null, {
                        ...this.editingProfile().filter,
                        [kind]: newConfig,
                    })
                }
            />
        );
    }
    render() {
        return (
            <div className="wa-sidebars-profile">
                <WaSettingsProfileBar
                    config={this.props.config}
                    editingProfile={this.state.editingProfile}
                    onCreate={this.onCreateProfile}
                    onChange={this.onChange}
                    onEdit={this.onChangeEditingProfile}
                    onSelect={this.props.onSelect}
                />
                <div className="wa-sidebars-profile-content">
                    <div>
                        <div
                            className="wa-title"
                            style={{ marginBottom: "5px" }}
                        >
                            Наименование профиля:
                        </div>
                        <input
                            type="text"
                            {...(this.editingProfile().key == "common"
                                ? { readOnly: true }
                                : {})}
                            value={this.editingProfile().profile}
                            onChange={(e) =>
                                this.onChangeProfile(e.target.value)
                            }
                            style={{ width: "100%" }}
                        />
                        <div
                            style={{ marginTop: "5px", marginBottom: "5px" }}
                            className="wa-title"
                        >
                            Укажите настройки фильтров (для каких страниц будут
                            использоваться сайдбары текущего профиля):
                        </div>
                    </div>
                    <WaTabs style={{ position: "relative", zIndex: "0" }}>
                        {{
                            Записи: this.resourcesAccumulator("posts"),
                            Страницы: this.resourcesAccumulator("pages"),
                            Архивы: this.resourcesAccumulator("cats"),
                        }}
                    </WaTabs>
                    <div className="wa-sidebars-profile-buttons">
                        {this.state.editingProfile != "common" && (
                            <button
                                className="button button-error"
                                onClick={() => this.onProfileDelete()}
                            >
                                Удалить
                            </button>
                        )}
                        <button
                            className="button button-primary"
                            onClick={() =>
                                this.props.onSelect(this.state.editingProfile)
                            }
                        >
                            Выбрать для настройки виджетов
                        </button>
                    </div>
                </div>
                {this.state.deleteQuery && (
                    <div
                        className="wa-sidebars-profile--delete-query"
                        onClick={this.onProfileDeleteQueryOutsideClick}
                    >
                        <div className="wa-sidebars-profile--delete-query-container">
                            <Info>
                                Вы действительно хотите удалить профиль "
                                {this.editingProfile().profile}"?
                                <br />
                                Вместе с ним будут удалены все связанные
                                настройки фильтров страниц и настройки
                                связанного набора сайдбаров и их виджетов...
                            </Info>
                            <div className="wa-sidebars-profile-buttons">
                                <button
                                    className="button button-error"
                                    onClick={() => this.onProfileDelete(true)}
                                >
                                    Удалить
                                </button>
                                <button
                                    className="button button-primary"
                                    onClick={() =>
                                        this.setState({ deleteQuery: false })
                                    }
                                >
                                    Отмена
                                </button>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        );
    }
}

export default WaSettingsProfile;
