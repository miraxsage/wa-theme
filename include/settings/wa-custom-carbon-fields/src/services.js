export function dashIconByType(type){
    switch(type){
        case "settings": type = "dashicons-admin-generic"; break;
        case "visible": type = "dashicons-visibility"; break;
        case "unvisible": case "hidden": type = "dashicons-hidden"; break;
        case "add": case "plus": type = "dashicons-plus-alt2"; break;
        case "info": type = "dashicons-info-outline"; break;
        case "error": case "warning": type = "dashicons-warning"; break;
        case "customize": type = "dashicons-admin-customizer"; break;
        case "cross": case "not": type = "dashicons-no-alt"; break;
        case "back-arrow": type = "dashicons-arrow-left-alt"; break;
        default: type = "dashicons-" + type;
    }
    return type;
}