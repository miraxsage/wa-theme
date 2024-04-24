export const Base64 = {
    // private property
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

    // public method for encoding
    encode: function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output =
                output +
                this._keyStr.charAt(enc1) +
                this._keyStr.charAt(enc2) +
                this._keyStr.charAt(enc3) +
                this._keyStr.charAt(enc4);
        } // Whend

        return output;
    }, // End Function encode

    // public method for decoding
    decode: function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }

            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
        } // Whend

        output = Base64._utf8_decode(output);

        return output;
    }, // End Function decode

    // private method for UTF-8 encoding
    _utf8_encode: function (string) {
        var utftext = "";
        string = string.replace(/\r\n/g, "\n");

        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            } else if (c > 127 && c < 2048) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            } else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }
        } // Next n

        return utftext;
    }, // End Function _utf8_encode

    // private method for UTF-8 decoding
    _utf8_decode: function (utftext) {
        var string = "";
        var i = 0;
        var c, c1, c2, c3;
        c = c1 = c2 = 0;

        while (i < utftext.length) {
            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            } else if (c > 191 && c < 224) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        } // Whend

        return string;
    }, // End Function _utf8_decode
};

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
    const canvas = getTextWidth.canvas || (getTextWidth.canvas = document.createElement("canvas"));
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

function debounce(id, func, delay = 500) {
    if (!debounce.tokens) debounce.tokens = {};
    if (id in debounce.tokens) {
        const token = debounce.tokens[id];
        const timeFromLastLaunch = Date.now() - (token.lastLaunch ?? 0);
        if (token.timeoutId) clearTimeout(token.timeoutId);
        if (timeFromLastLaunch > delay) {
            func();
            debounce.tokens[id].lastLaunch = Date.now();
        } else {
            token.timeoutId = setTimeout(() => {
                func();
                token.lastLaunch = Date.now();
                token.timeoutId = null;
            }, delay - timeFromLastLaunch);
        }
    } else {
        debounce.tokens[id] = {
            lastLaunch: Date.now(),
            timeoutId: null,
        };
        func();
    }
}
