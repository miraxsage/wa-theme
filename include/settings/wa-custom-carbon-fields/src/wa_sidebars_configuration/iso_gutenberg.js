import { PureComponent, createRef } from "react";

export default class IsoGutenberg extends PureComponent{
    constructor(props){
        super();
        this.containerRef = createRef();
        this.mounted = false;
    }
    componentDidMount(){
        if(this.mounted || !this.containerRef.current)
            return;
        this.mounted = true;
        window.eject_isolated_gutenberg(this.containerRef.current, this.props.content, this.props.onChange);
    }
    render(){
        return <div className="wa-iso-gutenberg-editor-container" ref={this.containerRef}></div>;
    }
}