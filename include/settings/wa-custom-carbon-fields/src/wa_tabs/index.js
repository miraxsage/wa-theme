import { React } from "react";
import { Component } from "@wordpress/element";
import "./style.scss";
import WaTab from "./wa_tab";

class WaTabs extends Component {
    constructor(props) {
        super(props);
        this.state = { activeTab: Object.keys(props.children)[0] };
        this.onActiveTabChange = this.onActiveTabChange.bind(this);
    }

    onActiveTabChange(newActiveTab) {
        newActiveTab = newActiveTab.replace("_", " ");
        if (newActiveTab !== this.state.activeTab)
            this.setState({ activeTab: newActiveTab });
    }

    render() {
        const { children, header, footer } = this.props;
        return (
            <div
                style={this.props.style ?? {}}
                class="wa-tabs cf-container cf-container-carbon_fields_container_wa_common_settings cf-container-theme-options cf-container--tabbed cf-container--tabbed-horizontal"
            >
                <div class="cf-container__tabs cf-container__tabs--tabbed-horizontal">
                    <ul class="cf-container__tabs-list">
                        {Object.keys(children).map((k) => (
                            <WaTab
                                title={k}
                                active={k == this.state.activeTab}
                                onClick={this.onActiveTabChange}
                            />
                        ))}
                    </ul>
                </div>
                <div class="cf-container__fields">
                    {Object.keys(children).map((k) => (
                        <div
                            style={{
                                display:
                                    k == this.state.activeTab
                                        ? "block"
                                        : "none",
                                position: "relative",
                            }}
                            class="cf-field__body"
                        >
                            {typeof children[k] === "function"
                                ? children[k]({
                                      onActiveTabChange: this.onActiveTabChange,
                                  })
                                : children[k]}
                        </div>
                    ))}
                </div>
            </div>
        );
    }
}

export default WaTabs;
