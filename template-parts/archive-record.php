<?php

    $settings = WaThemeSettings::get();
    $detailed_mode = $settings->archive__img_position == "aside";

    $permalink = get_post_permalink($post->ID);
    $title = get_the_title();
    $preview = get_the_excerpt();
    if(empty($preview)) {
        $preview = mb_substr(sanitize_text_field(get_the_content()), 0, 300);
        $preview .= mb_strlen($preview) == 300 ? " ..." : "";
    }

if(!function_exists("wa_print_archive_record_part")) {
    function wa_print_archive_record_part($part, $context)
    {
        extract($context);
        switch ($part){
            case "img":
                if($settings->archive__img_position != "outside" && $settings->archive__img_position != "aside")
                    wa_the_post_record_header($post, "archive");
                break;
            case "header": ?>
                <<?= semantics_tag("archive_semantics_cell__headline") ?> class="wa-archive-title"<?= schema_item("archive_schema_cell__headline", " ") ?>>
                    <a href="<?= $permalink ?>" rel="bookmark"><?= $title ?></a>
                </<?= semantics_tag("archive_semantics_cell__headline") ?>>
                <?php
                break;
            case "meta": case "meta_above":
                wa_subtemplate("meta", null, ["target" => "archive"]);
                break;
            case "excerpt": ?>
                <div class="wa-archive-excerpt"<?= schema_item("archive_schema_cell__excerpt", " ") ?>>
                    <p><?= $preview ?></p>
                </div>
                <?php
                break;
            case "more": ?>
                <div class="wa-read-more">
                    <a href="<?= $permalink ?>"><?= $settings->archive__more_label ?></a>
                </div>
                <?php
                break;
            case "header_open":
                echo "<".semantics_tag("archive_semantics_cell__header").">";
                break;
            case "header_close":
                echo "</".semantics_tag("archive_semantics_cell__header").">";
                break;
        }
    }
}

if($detailed_mode){ ?>
    <<?= semantics_tag("archive_semantics_cell__article") ?> class="wa-archive-cell"<?= schema_item("archive_schema_cell__article_container", " ") ?>>
    <div class="wa-archive-post">
        <div class="wa-archive-post-content wa-archive-sides">
            <div class="wa-archive-side1">
                <?= wa_the_post_record_header($post, "archive"); ?>
            </div>
            <div class="wa-archive-side2">
<?php }
else { ?>
    <<?= semantics_tag("archive_semantics_cell__article") ?> class="wa-archive-cell"<?= schema_item("archive_schema_cell__article_container", " ") ?>>
        <div class="wa-archive-post">
            <?php if($settings->archive__img_position == "outside")
                wa_the_post_record_header($post, "archive"); ?>
            <div class="wa-archive-post-content">

<?php }

$context = compact("permalink", "title", "preview", "post", "settings");

$blocks_sequence = $settings->archive__blocks_sequence;

foreach($blocks_sequence["sequence"] as $part => $visible){
    if(!$visible)
        continue;
    if(preg_match("/^block_(\\d+)$/", $part, $block_num_match) === 1){
        echo do_shortcode(base64_decode($blocks_sequence["blocks"][intval($block_num_match[1]) - 1]));
    }
    else
        wa_print_archive_record_part($part, $context);
}

if($detailed_mode)
    echo "</div>";
echo "</div></div></".semantics_tag("archive_semantics_cell__article").">";
