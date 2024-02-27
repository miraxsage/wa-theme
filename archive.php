<?php
$GLOBALS["wa_is_archive_template_render"] = true;
wa_template("frame", null, "begin"); ?>

    <div class="wa-archive-section">
        <?php if(WaThemeSettings::get()->archive__breadcrumbs)
                wa_subtemplate("breadcrumbs"); ?>
        <<?= semantics_tag("main_content") ?>>
            <?php wa_the_archive_title(); ?>
            <?php if(WaThemeSettings::get()->archive__category_description_position == "above")
                  wa_the_category_description(); ?>
            <div class="wa-archive-grid">
                <?php while(have_posts()) {
                    the_post();
                    wa_subtemplate("archive-record", null);
                } ?>
            </div>
            <?php if(WaThemeSettings::get()->archive__category_description_position == "below")
                wa_the_category_description(); ?>
        </<?= semantics_tag("main_content") ?>>
    </div>

    <?php wa_subtemplate("pagination"); ?>

<?php wa_template("frame", null, "end"); ?>