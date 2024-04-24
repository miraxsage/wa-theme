import WaAdvancedPillList from "../wa_advanced_pill_list";
import WaBlocksSequence from "../wa_blocks_sequence";
import WaSchemaInfo from "../wa_schema_info";
import WaTabs from "../wa_tabs";
import WaToggleButtonsList from "../wa_toggle_buttons_list";

function SchemaSettings({ target, config, onChange }) {
    let targetSingular = target.replace(/s$/, "");
    targetSingular = targetSingular == "post" ? "record" : targetSingular;
    return (
        <div className={"wa-schema-container"}>
            <div className="cf-field cf-wa-html">
                <div className="wa-html wa-html--description cf-field__help">
                    Укажите значения Schema.org-атрибутов для соответствующих элементов
                    {target == "pages" ? "каждой страницы" : target == "posts" ? "каждой записи" : "каждого архива"}
                </div>
            </div>
            {Object.entries(config).map(([key, val]) => {
                return (
                    <div className="cf-field cf-wa-schema-info">
                        <WaSchemaInfo
                            id={target + "_schema__" + key}
                            key={target + "_schema__" + key}
                            value={val}
                            field={{
                                label: wa_common__profiled_settings_configs[targetSingular + "_schema__" + key][
                                    "title"
                                ],
                            }}
                            onChange={(id, val) =>
                                onChange(target + "_schema", {
                                    ...config,
                                    [key]: val,
                                })
                            }
                        />
                    </div>
                );
            })}
        </div>
    );
}

function SemanticsSettings({ target, config, onChange }) {
    let targetSingular = target.replace(/s$/, "");
    targetSingular = targetSingular == "post" ? "record" : targetSingular;
    return (
        <div className={"wa-semantics-container"}>
            <div className="cf-field cf-wa-html">
                <div className="wa-html wa-html--description cf-field__help">
                    Укажите значения вариантов исполнения для соответствующих элементов
                    {target == "pages" ? "каждой страницы" : target == "posts" ? "каждой записи" : "каждого архива"}
                </div>
            </div>
            {Object.entries(config).map(([key, val]) => (
                <div className="cf-field cf-wa-toggle-button-list">
                    <WaToggleButtonsList
                        id={target + "_semantics__" + key}
                        key={target + "_semantics__" + key}
                        value={val}
                        field={{
                            label: wa_common__profiled_settings_configs[targetSingular + "_semantics__" + key]["title"],
                            description:
                                wa_common__profiled_settings_configs[targetSingular + "_semantics__" + key][
                                    "description"
                                ],
                            mode: "radio",
                            buttons: JSON.stringify(
                                val.split(",").map((pair) => {
                                    const [k, v] = pair.split(":");
                                    return {
                                        key: k,
                                        text: k,
                                        act: !!Number(v),
                                    };
                                })
                            ),
                        }}
                        onChange={(id, val) =>
                            onChange(target + "_semantics", {
                                ...config,
                                [key]: val,
                            })
                        }
                    />
                </div>
            ))}
        </div>
    );
}

export function compileReorderedChoicesConfig(val, config) {
    const savedChoices = val.split(",");
    const choices = {};

    for (const choice of savedChoices) {
        const [key, val] = choice.split(":");
        const checked = !!Number(val);
        let keyMatch = null;
        if (key in config["choices"] || (keyMatch = key.match(/^block_(\d+)$/))) {
            if (keyMatch) {
                choices[key] = {
                    label: "Блок " + keyMatch[1],
                    checked,
                };
            } else {
                if (typeof config["choices"][key] == "object") choices[key] = { ...config["choices"][key], checked };
                else
                    choices[key] = {
                        label: String(config["choices"][key]),
                        checked,
                    };
            }
        }
    }
    for (const key in config["choices"]) {
        if (!(key in choices))
            choices[key] =
                typeof config["choices"][key] == "object"
                    ? config["choices"][key]
                    : { label: config["choices"][key], checked: true };
    }
    return choices;
}

export default function WaProfiledTargetSettings({ target = "pages", config, style, onChange }) {
    const onChangeHandler = (setting) => (id, newVal) => {
        onChange({ ...config, [setting]: newVal });
    };
    let targetSingular = target.replace(/s$/, "");
    targetSingular = targetSingular == "post" ? "record" : targetSingular;
    const reorderedChoices = compileReorderedChoicesConfig(
        JSON.parse(config["blocks_sequence"]).sequence,
        wa_common__profiled_settings_configs[targetSingular + "__blocks_sequence"]
    );
    return (
        <WaTabs style={style}>
            {{
                Последовательность: (
                    <WaBlocksSequence
                        field={{
                            label: "Последовательность блоков",
                            description: `Укажите видимость и порядок следования блоков для ${
                                target == "pages"
                                    ? "каждой страницы"
                                    : target == "posts"
                                    ? "каждой записи"
                                    : "каждого архива"
                            }`,
                            reordered_choices: reorderedChoices,
                        }}
                        value={config.blocks_sequence}
                        onChange={onChangeHandler("blocks_sequence")}
                    />
                ),
                Schema: <SchemaSettings target={target} config={config.schema} onChange={onChangeHandler("schema")} />,
                Семантика: (
                    <SemanticsSettings
                        target={target}
                        config={config.semantics}
                        onChange={onChangeHandler("semantics")}
                    />
                ),
            }}
        </WaTabs>
    );
}
