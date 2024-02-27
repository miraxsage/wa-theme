import IsolatedBlockEditor from '@automattic/isolated-block-editor';
import { render } from "@wordpress/element";
import { addFilter } from "@wordpress/hooks";
import domReady from "@wordpress/dom-ready";
import { MediaUpload } from '@wordpress/media-utils';
import { mediaUpload } from '@wordpress/editor';

domReady(function(){
    wa_iso_gutenberg_default_settings.editor.mediaUpload = mediaUpload;
    addFilter('editor.MediaUpload', 'wr/media-upload', () => MediaUpload);
    window.eject_isolated_gutenberg = function(container, content = "", onChange = () => {}){
        container.innerHTML = "";
        render(
            <IsolatedBlockEditor
                settings={ wa_iso_gutenberg_default_settings }
                onSaveContent={ (html) => onChange(html) }
                onLoad={ (parse) => parse(content) }
               // onError={ () => document.location.reload() }
            >
            </IsolatedBlockEditor>,
            container
        );
    }
});