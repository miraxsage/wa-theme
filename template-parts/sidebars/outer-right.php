<?php
$config = sidebar_config("outer_right");
if(!empty($config)): ?>
<<?= semantics_tag("outer_right_sidebar") ?> class="wa-sidebar-outright"<?= schema_item("outer_right_sidebar", " ") ?>>
    <?php output_sidebar($config, "wa-sidebar-content"); ?>
</<?= semantics_tag("outer_right_sidebar") ?>>
<?php endif; ?>