const url_param = (url = "", pushState = true) => {
    const pattern = new RegExp("^(https?:\\/\\/)?((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|((\\d{1,3}\\.){3}\\d{1,3}))(:\\d+)?(\\/[-a-z\\d%_.~+]*)*(\\?[;&a-z\\d%_.~+=-]*)?(#[-a-z\\d_]*)?$", "i");

    let addr = window.location.origin + window.location.pathname;
    let params = new URLSearchParams(window.location.search);

    if (pattern.test(url)) {
        addr = url.split("?")[0];
        params = new URLSearchParams(url.split("?")[1]);
    }

    return {
        set: (key = "", value = "", reload = false) => {
            params.set(key, value);
            let newUrl = addr + "?" + params.toString();
            if (pushState) window.history.pushState({ path: newUrl }, "", newUrl);
            else return newUrl;

            if (reload) window.location.reload();
        },

        get: (key = "") => {
            return params.get(key);
        },

        delete: (key = "", reload = false) => {
            params.delete(key);
            let newUrl = addr + "?" + params.toString();
            if (pushState) window.history.pushState({ path: newUrl }, "", newUrl);
            else return newUrl;

            if (reload) window.location.reload();
        },

        deleteAll: (reload = false) => {
            if (pushState) window.history.pushState({ path: addr }, "", addr);
            else return addr;

            if (reload) window.location.reload();
        },

        encode: () => {
            if (pattern.test(addr)) return encodeURI(addr);
            else return encodeURIComponent(addr);
        },

        decode: () => {
            if (pattern.test(addr)) return decodeURI(addr);
            else return decodeURIComponent(addr);
        }
    }
}
// Author link: linkbox.su/r/mixno35