<?php
$config = sidebar_config("outer_left");
if(!empty($config)):
    $css = extract_sidebar_line_css_props($config["lines"][0]);
    $css = empty($css) ? "" : "style=\"".str_replace("\"", "'", $css)."\"";
?>
<<?= semantics_tag("outer_left_sidebar") ?> <?= $css ?> class="wa-sidebar-outleft"<?= schema_item("outer_left_sidebar", " ") ?>>
    <?php output_sidebar($config, "wa-sidebar-content"); ?>
</<?= semantics_tag("outer_left_sidebar") ?>>
<?php endif; ?>