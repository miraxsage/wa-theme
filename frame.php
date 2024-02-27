<?php if($args == "begin") : ?>
<?php get_header();  ?>
    <div class="wa-body wa-post">
        <?php wa_subtemplate("sidebars/outer-left"); ?>
        <div class="wa-outer">
            <?php wa_subtemplate("widgets-header"); ?>
            <div class="wa-central">
                <?php wa_subtemplate("sidebars/inner-left"); ?>
                    <div class="wa-content" id="content">
                        <?php endif; if($args == "end" || arr_has($args, "part", "end")) : ?>
                    </div>
                <?php wa_subtemplate("sidebars/inner-right"); ?>
            </div>
            <?php if(arr_has($args, "similars", true))
                      wa_subtemplate("similar-records", null, $post); ?>
            <?php wa_subtemplate("widgets-footer"); ?>
        </div>
        <?php wa_subtemplate("sidebars/outer-right"); ?>
    </div>
<?php get_footer() ?>
<?php endif; ?>
