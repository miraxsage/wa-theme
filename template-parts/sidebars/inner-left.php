<?php
    $config = sidebar_config("inner_left");
    if(!empty($config)): ?>
<<?= semantics_tag("inner_left_sidebar") ?> class="wa-sidebar-inleft"<?= schema_item("inner_left_sidebar", " ") ?>>
    <?php output_sidebar($config, "wa-sidebar-content"); ?>
</<?= semantics_tag("inner_left_sidebar") ?>>
<?php endif; ?>