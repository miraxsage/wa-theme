<?php

if(!empty($post = wa_get_post($args))):
$similars = similar_posts($post);
if(empty($similars))
    return;

if(!function_exists("wa_print_similar_part")) {
    function wa_print_similar_part($part, $context)
    {
        extract($context);
        switch ($part){
            case "img":
                if(!$settings->record_similar__img_outside)
                    wa_the_post_record_header($post, "similar");
                break;
            case "header": ?>
                <a rel="bookmark" href="/">
                    <div class="wa-related-title"><?= $post->post_title ?></div>
                </a>
                <?php
                break;
            case "meta":
                $views_count = intval(get_post_meta($post->ID, 'views', true));
                ?>
                <div class="wa-related-meta">
                    <span class="wa-related-views"><?= $views_count ?></span>
                    <span class="wa-related-comments"><?= $post->comment_count ?></span>
                </div>
                <?php
                break;
            case "content":
                $preview = get_the_excerpt($post);
                if(empty($preview)) {
                    $preview = mb_substr(sanitize_text_field(get_the_content(null, null, $post)), 0, 300);
                    $preview .= mb_strlen($preview) == 300 ? " ..." : "";
                    if(empty($preview))
                        $preview = "...";
                }
                echo "<p>$preview</p>";
                break;
            case "more":
                $permalink = get_post_permalink($post->ID);
                ?>
                <a href="<?= $permalink ?>"><div class="wa-related-readmore">Читать далее »</div></a>
                <?php
                break;
        }
    }
}

$settings = WaThemeSettings::get();

?>

<div class="wa-related-label"><?= $settings->record__similar_records_label ?></div>
<div class="wa-related">
<?php
//start for similars
foreach($similars as $similar):
    $context = ["settings" => $settings, "post" => $similar];
?>
    <article class="wa-related-post">
        <?php
        if($settings->record_similar__img_outside)
            wa_the_post_record_header($similar, "similar"); ?>

        <div class="wa-related-container">

        <?php

            $content_has_been = false;
            $header_status = "";
            foreach($settings->record_similar__blocks_sequence as $part => $visible) {
                if(!$visible)
                    continue;
                if ($part == "content") {
                    if ($header_status == "started") {
                        echo "</header>";
                        $header_status = "ended";
                    } else
                        $header_status = "starting";
                }
                if(empty($header_status)){
                    echo "<header>";
                    $header_status = "started";
                }
                wa_print_similar_part($part, $context);
                if($header_status == "starting"){
                    echo "<header>";
                    $header_status = "started";
                }
            }
            if ($header_status == "started")
                echo "</header>";
            ?>

        </div>
    </article>

<?php
//end foreach for similars
endforeach;

endif;
?>
</div>

