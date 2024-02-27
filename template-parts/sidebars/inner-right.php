<?php
$config = sidebar_config("inner_right");
if(!empty($config)): ?>
<<?= semantics_tag("inner_right_sidebar") ?> class="wa-sidebar-inright"<?= schema_item("inner_right_sidebar", " ") ?>>
    <?php output_sidebar($config, "wa-sidebar-content"); ?>
</<?= semantics_tag("inner_right_sidebar") ?>>
<?php endif; ?>