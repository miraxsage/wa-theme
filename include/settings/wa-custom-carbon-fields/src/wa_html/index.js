import { React, createRef } from "react";
import { Component } from "@wordpress/element";
import classes from "classnames";
import "./style.scss";

class WaHtml extends Component {
    rootRef = createRef();

    constructor(props) {
        super(props);
    }

    componentDidMount() {
        jQuery(this.rootRef.current).html(this.props.field.html);
        if (this.props.field.style)
            jQuery(this.rootRef.current)[0].style.cssText =
                this.props.field.style;
        let saveHeader =
            this.props.saveHeader ?? this.props.field.saveHeader ?? false;
        let saveDivider =
            this.props.saveDivider ?? this.props.field.saveDivider ?? false;
        if (!saveHeader && typeof jQuery !== "undefined")
            jQuery(this.rootRef.current)
                .closest(".cf-field")
                .find(".cf-field__head")
                .remove();
        if (!saveDivider && typeof jQuery !== "undefined")
            jQuery(this.rootRef.current)
                .closest(".cf-field")
                .next(".cf-field")
                .css("border", "0px");
        if (this.props.field.mode == "category_container_description") {
            let jQElement = jQuery(this.rootRef.current);
            let fieldset = jQElement.closest("fieldset");
            if (fieldset.length == 0) return;
            let parent = fieldset.parent();
            jQElement.detach();
            if (parent.is("td"))
                parent
                    .prev()
                    .css({
                        "vertical-align": "top",
                        padding: "30px 0px 0px 0px",
                    })
                    .append(jQElement);
            else {
                parent.prepend(jQElement);
                parent.find(".description").css("margin", "6px 0px 8px 0px");
            }
        }
    }

    render() {
        let {
            field: { mode },
        } = this.props;
        return (
            <div
                ref={this.rootRef}
                className={classes("wa-html", {
                    "wa-html--description cf-field__help":
                        mode == "description",
                    "wa-html--container-description":
                        mode == "category_container_description",
                })}
            ></div>
        );
    }
}

export default WaHtml;
