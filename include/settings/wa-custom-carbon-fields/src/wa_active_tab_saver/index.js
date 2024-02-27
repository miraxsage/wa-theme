import { Component } from '@wordpress/element';
import { createRef } from "react"

function next(element, selector){
    if(!element)
        return null;
    let nextSibling = element;
    do nextSibling = nextSibling.nextElementSibling;
    while (nextSibling && !nextSibling.matches(selector));
    return nextSibling;
}
class WaActiveTabSaver extends Component{
    constructor(props){
        super();
        this.state = { index: 0 };
        this.ref = createRef();
        this.container = props.field.container ?? "container";
    }
    componentDidMount(){
        if(!this.ref.current)
            return;
        let urlParams = new URLSearchParams(window.location.search);
        var curIndex = urlParams.get("wa-carbon-active-tab__" + this.container);
        if(curIndex){ 
            curIndex = parseInt(curIndex);
            //window.history.pushState({}, document.title, window.location.pathname.replace("wa-carbon-active-tab__" + this.container + "=" + curIndex, ""));
        }
        if(curIndex){
            this.setState({index: curIndex});
            requestAnimationFrame(() => {
                this.tabsContainer.children[this.state.index - 1].querySelector("button").dispatchEvent(new MouseEvent("click", {
                    "view": window,
                    "bubbles": true,
                    "cancelable": false
                }));
            });
        }
        this.tabsContainer = this.ref.current.closest(".cf-container").querySelector("ul.cf-container__tabs-list");
        let curField = this.ref.current.closest(".cf-field");
        curField.style.display = "none";
        curField.querySelector(".cf-field__head").remove();
        let nextField = next(curField, ".cf-field");
        if(nextField)
            nextField.style.border = "0px";
        this.tabsContainer.addEventListener("click", () => {
            requestAnimationFrame(() => {
                let curIndex = Array.from(this.tabsContainer.children).reduce(
                    (prev, curr, i) => 
                        curr.classList.contains("cf-container__tabs-item--current") && !prev ? i + 1 : prev, 0);
                this.setState({index: curIndex});
            });
        });
    }
    render(){
        return <input ref={this.ref} type="hidden" name={"wa-carbon-active-tab__" + this.container} value={this.state.index} />
    }
}
export default WaActiveTabSaver;