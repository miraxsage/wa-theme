<?php
$config = sidebar_config("footer");
if(!empty($config)): ?>
<<?= semantics_tag("theme_footer") ?> class="wa-footer"<?= schema_item("theme_footer", " ") ?>>
    <?php output_sidebar($config, "wa-footer-section1", true); ?>
</<?= semantics_tag("theme_footer") ?>>
<div class="wa-copyright">***Copyrights html block***</div>
<?php endif; ?>