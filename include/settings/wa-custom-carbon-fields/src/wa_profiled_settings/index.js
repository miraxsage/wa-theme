import { React, createRef } from "react";
import { Component } from "@wordpress/element";
import WaTabs from "../wa_tabs/index";
import classes from "classnames";
import WaSidebarsFrames from "./wa_sidebars_frames";
import "./style.scss";
import WaFrameConfiguration from "./wa_frame_configuration";
import WaSettingsProfileSelector from "./wa_settings_profile/wa_settings_profile_selector";
import WaProfiledTargetSettings from "./wa_profiled_target_settings";
import Info from "../info";
import WaToggleCheckbox from "../wa_toggle_checkbox";

class WaProfiledSettings extends Component {
    sidebars = [
        "header",
        "footer",
        "inner_left",
        "inner_right",
        "outer_left",
        "outer_right",
    ];

    checkStoreObj(store) {
        if (!store || typeof store !== "object" || !(store instanceof Array))
            return false;
        return true;
    }

    fixAbsentKeys() {
        let store = this.chosenProfile().config;
        let hasAbsentKeys = false;
        this.sidebars.forEach((sbr) => {
            if (store[sbr].lines.length > 0) {
                store[sbr].lines.forEach((ln, i) => {
                    if (!ln.key) {
                        store[sbr].lines[i].key = Math.floor(
                            Math.random() * Date.now()
                        ).toString(16);
                        hasAbsentKeys = true;
                    }
                    ln.items.forEach((it, j) => {
                        if (!it.key) {
                            store[sbr].lines[i].items[j].key = Math.floor(
                                Math.random() * Date.now()
                            ).toString(16);
                            hasAbsentKeys = true;
                        }
                    });
                });
            }
        });
        return hasAbsentKeys;
    }

    constructor(props) {
        super(props);
        this.config = null;
        this.action = createRef();
        this.activeTab = createRef();
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has("target")) {
            const target = urlParams.get("target");
            if (target == "pages") this.activeTab.current = 0;
            if (target == "posts") this.activeTab.current = 1;
            if (target == "archives") this.activeTab.current = 2;
        }
        this.state = { chosenProfile: "common" };
        this.frameConfigControl = this.frameConfigControl.bind(this);
        this.onChange = this.onChange.bind(this);
        this.openProfile = this.openProfile.bind(this);
    }

    onFrameConfigChanged(frame_name) {
        return (newConfig) =>
            this.onChange(
                this.config.map((c) =>
                    c.key == this.state.chosenProfile
                        ? {
                              ...c,
                              config: {
                                  ...c.config,
                                  sidebars: {
                                      ...c.config.sidebars,
                                      [frame_name]: newConfig,
                                  },
                              },
                          }
                        : c
                )
            );
    }
    componentDidMount() {
        if (typeof this.activeTab.current == "number") {
            this.activeTab.current = null;
            this.setState({ ...this.state });
        }
    }
    onSettingsChanged(target) {
        return (newConfig) =>
            this.onChange(
                this.config.map((c) =>
                    c.key == this.state.chosenProfile
                        ? {
                              ...c,
                              config: {
                                  ...c.config,
                                  [target]: {
                                      ...c.config[target],
                                      ...newConfig,
                                  },
                              },
                          }
                        : c
                )
            );
    }
    onChange(newConfig) {
        if (!newConfig.find((c) => c.key == this.state.chosenProfile)) {
            let chosenIndex = this.config.findIndex(
                (c) => c.key == this.state.chosenProfile
            );
            if (chosenIndex > 0) chosenIndex--;
            else chosenIndex++;
            this.setState({
                ...this.state,
                chosenProfile: this.config[chosenIndex].key,
            });
        }
        this.config = newConfig;
        let newConfigJson = JSON.stringify(newConfig);
        if (newConfigJson != this.props.value)
            this.props.onChange(this.props.id, newConfigJson);
        else this.setState({});
    }

    frameConfigControl(frame_name) {
        return (
            <WaFrameConfiguration
                name={frame_name}
                config={
                    this.config.find((c) => c.key == this.state.chosenProfile)
                        .config.sidebars[frame_name]
                }
                onChange={this.onFrameConfigChanged(frame_name)}
            />
        );
    }
    chosenProfile() {
        return this.config.find((p) => p.key == this.state.chosenProfile);
    }
    openProfile(e) {
        e?.preventDefault?.();
        this.action.current = (doAction) => {
            doAction("openProfile");
            this.action.current = null;
            this.setState({ ...this.state });
        };
        this.setState({ ...this.state });
    }
    settingsInfo(kind) {
        const forKindLabel =
            kind == "pages"
                ? "страниц"
                : kind == "posts"
                ? "записей"
                : "архивов";
        const kindLabel =
            kind == "pages"
                ? "страницам"
                : kind == "posts"
                ? "записям"
                : "архивам";
        return (
            <>
                {kind != "widgets" && this.state.chosenProfile != "common" && (
                    <WaToggleCheckbox
                        value={this.chosenProfile().filter[kind].mode != "none"}
                        onChange={(id, checked) =>
                            this.onChange(
                                this.config.map((p) =>
                                    p.key == this.state.chosenProfile
                                        ? {
                                              ...p,
                                              filter: {
                                                  ...p.filter,
                                                  [kind]: {
                                                      mode: checked
                                                          ? "all"
                                                          : "none",
                                                      ids: [],
                                                  },
                                              },
                                          }
                                        : p
                                )
                            )
                        }
                        field={{ label: "Настроить" }}
                        withoutHeader={true}
                        style={{ margin: "5px 0px 5px 0px" }}
                    />
                )}
                <Info
                    style={{
                        marginTop:
                            this.state.chosenProfile == "common"
                                ? "0px"
                                : "3px",
                    }}
                >
                    {kind != "widgets" &&
                    this.chosenProfile().filter[kind].mode == "none" ? (
                        'Настройки профиля "' +
                        this.chosenProfile().profile +
                        '" не активны для всех ' +
                        forKindLabel
                    ) : this.state.chosenProfile == "common" ? (
                        <>
                            {(kind == "widgets"
                                ? "Настроенные ниже виджеты появятся на всех страницах"
                                : "Настройки ниже будут соответственно применены ко всем " +
                                  kindLabel) +
                                ", для которых не определено других профилей, стоящих выше в "}
                            <a
                                href="admin.php?page=wa-profiled-settings.php&action=adjust-profile"
                                onClick={this.openProfile}
                            >
                                списке
                            </a>
                        </>
                    ) : (
                        <>
                            {kind == "widgets"
                                ? "Настроенные ниже виджеты появятся на всех страницах, указанных в "
                                : "Настройки ниже будут соответственно применены ко всем " +
                                  kindLabel +
                                  ", указанных в "}
                            <a
                                href="admin.php?page=wa-profiled-settings.php&action=adjust-profile"
                                onClick={this.openProfile}
                            >
                                настройках
                            </a>{" "}
                            выбранного профиля "{this.chosenProfile().profile}",
                            если в списке профилей выше не определено других
                            подходящих
                        </>
                    )}
                </Info>
            </>
        );
    }
    render() {
        try {
            if (!this.config) this.config = JSON.parse(this.props.value);
            if (!this.config.any((c) => c.key == this.state.chosenProfile)) {
                this.setState({
                    ...this.state,
                    chosenProfile: this.config[this.config.length - 1].key,
                });
                return;
            }
            if (this.fixAbsentKeys()) {
                this.onChange(this.config, false);
                return;
            }
        } catch {}
        if (!this.checkStoreObj(this.config)) this.config = null;
        if (!this.config) return "state hasn`t been configured";
        let profileConfig = this.config.find(
            (p) => p.key == this.state.chosenProfile
        ).config;
        const { id, name, value, field } = this.props;
        return (
            <div
                className={classes("wa-sidebar-configuration")}
                ref={this.rootRef}
            >
                <input type="hidden" id={id} name={name} value={value} />
                {field.description ? (
                    <span className="wa-advanced-pill-list__description">
                        {field.description}
                    </span>
                ) : (
                    ""
                )}
                <WaSettingsProfileSelector
                    action={this.action.current}
                    config={this.config}
                    chosenProfile={this.state.chosenProfile}
                    onChange={this.onChange}
                    onSelect={(key) =>
                        this.setState({ ...this.state, chosenProfile: key })
                    }
                />
                <WaTabs selectedTab={this.activeTab.current}>
                    {{
                        ...Object.fromEntries(
                            [
                                ["Записи", "posts"],
                                ["Страницы", "pages"],
                                ["Архивы", "archives"],
                            ].map(([key, target]) => [
                                key,
                                <>
                                    {this.settingsInfo(target)}
                                    <WaProfiledTargetSettings
                                        target={target}
                                        config={profileConfig[target]}
                                        onChange={this.onSettingsChanged(
                                            target
                                        )}
                                        style={{
                                            display:
                                                this.chosenProfile().filter[
                                                    target
                                                ].mode == "none"
                                                    ? "none"
                                                    : "block",
                                            marginTop: "10px",
                                        }}
                                    />
                                </>,
                            ])
                        ),
                        Виджеты: (
                            <>
                                {this.settingsInfo("widgets")}
                                <WaTabs>
                                    {{
                                        Каркас: ({ onActiveTabChange }) => (
                                            <WaSidebarsFrames
                                                config={profileConfig.sidebars}
                                                onChange={this.onSettingsChanged(
                                                    "sidebars"
                                                )}
                                                onSettingsClick={(frame_name) =>
                                                    onActiveTabChange(
                                                        frame_name
                                                    )
                                                }
                                            />
                                        ),
                                        Header: this.frameConfigControl(
                                            "header"
                                        ),
                                        Footer: this.frameConfigControl(
                                            "footer"
                                        ),
                                        "Outer Left":
                                            this.frameConfigControl(
                                                "outer_left"
                                            ),
                                        "Outer Right":
                                            this.frameConfigControl(
                                                "outer_right"
                                            ),
                                        "Inner Left":
                                            this.frameConfigControl(
                                                "inner_left"
                                            ),
                                        "Inner Right":
                                            this.frameConfigControl(
                                                "inner_right"
                                            ),
                                    }}
                                </WaTabs>
                            </>
                        ),
                    }}
                </WaTabs>
            </div>
        );
    }
}

export default WaProfiledSettings;
