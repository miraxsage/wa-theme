jQuery(document).ready(function() {
    let cats = [];
    let inited = false;
    let cats_inited = false;
    let wp_cats_loaded = false;
    let postbox_gidrated = false;
    let postbox_inited = false;
    let waiting_time = 0;
    async function refresh_cats(){
        let response = await fetch("/wp-json/wp/v2/categories?context=view&per_page=100&orderby=name&order=asc&_fields=id%2Cname&_locale=user");
        cats = await response.json();
    }
    function find_cats_container(){
        let wp_cats_container = null;
        jQuery(".components-button.components-panel__body-toggle").each(function(){
            if(this.textContent == "Рубрики")
                wp_cats_container = jQuery(this.closest(".components-panel__body"));
        });
        return wp_cats_container;
    }
    function init_postbox(){
        let wp_cats_container = find_cats_container();
        let postbox = jQuery("#wa_main_category").closest(".postbox");
        let postbox_container = !postbox ? null : postbox.closest(".edit-post-meta-boxes-area");
        if(postbox.length == 0 || postbox_container.length == 0)
            return false;
        if(!wp_cats_container) {
            postbox_container.hide()
            return false;
        }
        postbox_container.show()
        if(postbox_container.prev() != wp_cats_container)
            postbox_container.insertAfter(wp_cats_container);
        if(!postbox_gidrated) {
            let header = postbox.find(".postbox-header").addClass("wa-main-category-header");
            let onmaincatfocus = function(){
                if(!wp_cats_loaded)
                    wp_cats_container.find('.components-button').click();
            };
            jQuery("#wa_main_category").click(onmaincatfocus);
            header.click(onmaincatfocus);
            postbox.find(".handle-actions > .handlediv").bind('focus focusout ', function (e) {
                if (e.type == "focus")
                    jQuery(this).closest(".postbox-header").addClass("focused");
                else
                    jQuery(this).closest(".postbox-header").removeClass("focused");
            });
            postbox.find(".handle-actions > .handlediv .toggle-indicator").addClass('wa-main-category-toggle-indicator');
            postbox.find(".ui-sortable-handle").css("cursor", "default").click(function () {
                postbox.find(".handle-actions > .handlediv").focus();
            });
            let mObs = new MutationObserver(function () {
                postbox.find(".handle-actions > :not(.handlediv)").remove();
            });
            mObs.observe(header[0], {childList: true, subtree: true, characterData: true, attributes: true});
            mObs = new MutationObserver(function () {
                init_postbox();
            });
            mObs.observe(header[0], {childList: true, subtree: true, characterData: true, attributes: true});
            let prevactivetab = null;
            jQuery(".edit-post-sidebar__panel-tabs").click(function(){
                requestAnimationFrame(() => {
                    let tabbtn = jQuery(".components-button.edit-post-sidebar__panel-tab.is-active");
                    if (tabbtn.length == 0 || tabbtn == prevactivetab)
                        return;
                    prevactivetab = tabbtn;
                    init_postbox();
                });
            });
            if(wp_cats_container) {
                mObs = new MutationObserver(refresh_cats_opts);
                mObs.observe(wp_cats_container[0], {childList: true, subtree: true});
            }
            postbox_gidrated = true;
        }
        return true;
    }
    function init(){
        if(waiting_time > 20)
            return;
        if(waiting_time == 0) {
            jQuery("#wa_main_category").on("change", function () {
                jQuery(this).attr("data-main-category", jQuery(this).find("option:checked").attr("value"));
            });
            jQuery(document).on('change', '.editor-post-taxonomies__hierarchical-terms-list input[type="checkbox"]', refresh_cats_opts);
        }
        if(!postbox_inited)
            postbox_inited = init_postbox();

        let wp_opts_list = jQuery('.editor-post-taxonomies__hierarchical-terms-list');
        let els = !wp_opts_list ? null : wp_opts_list.find('input[type="checkbox"]');
        if(!els || els.length == 0)
            setTimeout(init, 250);
        else
            refresh_cats_opts();
        waiting_time++;
    }
    async function refresh_cats_opts(){
        if(cats.length == 0)
            await refresh_cats();
        let wp_cats = jQuery('.editor-post-taxonomies__hierarchical-terms-list input[type="checkbox"]').toArray()
                        .filter(el => jQuery(el).is(":checked")).map(el => ({ name: jQuery(el).closest(".components-base-control__field").find("label.components-checkbox-control__label").html() }));
        if(wp_cats.some(wcat => !cats.some(cat => cat.name == wcat.name)))
            await refresh_cats();
        if(!wp_cats || wp_cats.length == 0)
            return;
        else
            wp_cats_loaded = true;
        let cats_opts = wp_cats.map(wcat => Object.assign(wcat, cats.find(cat => cat.name == wcat.name)));
        let opts_el = jQuery("#wa_main_category");
        opts_el.find("option").each(function(){
            if(jQuery(this).attr("value") == 0)
                return;
            jQuery(this).remove();
        });
        cats_opts.forEach(cat => opts_el.append("<option value=\"" + cat.id + "\">" + cat.name + "</option>"));
        let curid = opts_el.attr("data-main-category");
        curid = cats_opts.some(c => c.id == curid) ? curid : 0;
        opts_el[0].value = curid;
        if(opts_el[0].value == 0 && cats_opts.length > 0 && (inited || opts_el.attr("data-main-category") == 0))
            opts_el[0].value = cats_opts[0].id;
        inited = true;
    }
    init();
});