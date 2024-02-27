<?php
$config = sidebar_config("header");
if(!empty($config)): ?>
<<?= semantics_tag("theme_header") ?> class="wa-header"<?= schema_item("theme_header", " ") ?>>
    <?php output_sidebar($config, "wa-header-section1", true); ?>
</<?= semantics_tag("theme_header") ?>>
<?php endif; ?>