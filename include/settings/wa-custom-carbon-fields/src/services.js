export function dashIconByType(type) {
    switch (type) {
        case "settings":
            type = "dashicons-admin-generic";
            break;
        case "visible":
            type = "dashicons-visibility";
            break;
        case "unvisible":
        case "hidden":
            type = "dashicons-hidden";
            break;
        case "add":
        case "plus":
            type = "dashicons-plus-alt2";
            break;
        case "info":
            type = "dashicons-info-outline";
            break;
        case "error":
        case "warning":
            type = "dashicons-warning";
            break;
        case "customize":
            type = "dashicons-admin-customizer";
            break;
        case "cross":
        case "not":
            type = "dashicons-no-alt";
            break;
        case "back-arrow":
            type = "dashicons-arrow-left-alt";
            break;
        default:
            type = "dashicons-" + type;
    }
    return type;
}

export function capitalize(str) {
    if (typeof str != "string" || str.length == 0) return str;
    return str[0].toUpperCase() + str.slice(1).toLowerCase();
}

export function generateId() {
    return (Math.random() + 1).toString(36).substring(2, 10);
}

export function getTextWidth(text, elementToExtractFontStyle) {
    // re-use canvas object for better performance
    const canvas =
        getTextWidth.canvas ||
        (getTextWidth.canvas = document.createElement("canvas"));
    const context = canvas.getContext("2d");
    context.font = getCanvasFont(elementToExtractFontStyle);
    const metrics = context.measureText(text);
    return metrics.width;
}

function getCssStyle(element, prop) {
    return window.getComputedStyle(element, null).getPropertyValue(prop);
}

function getCanvasFont(el = document.body) {
    const fontWeight = getCssStyle(el, "font-weight") || "normal";
    const fontSize = getCssStyle(el, "font-size") || "16px";
    const fontFamily = getCssStyle(el, "font-family") || "Times New Roman";
    return `${fontWeight} ${fontSize} ${fontFamily}`;
}
