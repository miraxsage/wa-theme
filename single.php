<?php
$author_slug = get_the_author_meta('user_login', $post->post_author);
$author_name = get_the_author_meta('display_name', $post->post_author);
$author_link = get_author_posts_url($post->post_author);
$author_description = get_the_author_meta('description', $post->post_author);
$settings = WaThemeSettings::get();

$context = compact("author_slug", "author_name", "author_link", "author_description", "post", "settings");

function wa_print_block($block, $context){
    extract($context);
    switch ($block){
        case "breadcrumbs":
            wa_subtemplate("breadcrumbs");
            break;
        case "main_article_open": ?>
            <<?= semantics_tag("main_content") ?>><<?= semantics_tag("page_article") ?> class="wa-article"<?= schema_item("article_container", " ") ?>>
            <?php
            break;
        case "header_open":
            echo "<".semantics_tag("page_header").">";
            break;
        case "img":
            if(!$settings->record__img_outside): ?>
            <div class="wa-header-img">
                <?php if($settings->record__date_in_square)
                    wa_the_date_square($post->post_date); ?>
                <?php wa_the_post_thumbnail($post, "full"); ?>
            </div>
            <?php endif;
            break;
        case "header":
            echo "<".semantics_tag("page_headline").schema_item("h1", " ").">".get_the_title()."</".semantics_tag("page_headline").">";
            break;
        case "meta_above":
            wa_subtemplate("meta", null, $settings->record_meta__use_icons ? "full" : "inline");
            break;
        case "header_close":
            echo "</".semantics_tag("page_header").">";
            break;
        case "contents_list":
            wa_subtemplate("headlines");
            break;
        case "content":
            ?>
            <div class="entry-content"<?= schema_item("content_section", " ") ?>>
                <?= the_content(); ?>
            </div>
            <?php
            break;
        case "meta_below":
            wa_subtemplate("meta", null, "footer");
            break;
        case "main_article_close":
            if($settings->record__prev_next_links): ?>
                <div class="wa-post-nav">
                    <div class="wa-post-previous">
                        <?= previous_post_link("%link", '<span class="wa-left-arrow">←</span>Предыдущая Запись'); ?>
                    </div>
                    <div class="wa-post-next">
                        <?= next_post_link("%link", 'Следующая Запись<span class="wa-right-arrow">→</span>'); ?>
                    </div>
                </div>
            <?php endif;
            echo "</".semantics_tag("page_article")."></".semantics_tag("main_content").">";
            break;
        case "social_buttons":
            wa_subtemplate("socials");
            break;
        case "author":
            if(!$first): ?>
                <div class='block_divider'></div>
            <?php endif; ?>
            <div class="wa-authorship-title"><?= $settings->record__about_author_label ?></div>
            <div class="wa-authorship-block">
                <div class="wa-author-avatar">
                    <?= get_avatar($post, 65, '', 'Author’s avatar') ?>
                </div>
                <div class="wa-author-bio">
                    <?php if($settings->record__author_link_mode["about"]) : ?>
                        <a itemprop="url" rel="author" href="<?= $author_link ?>"><span itemprop="name"><?= $author_name ?></span></a>
                    <?php else: ?>
                        <span itemprop="name"><?= $author_name ?></span>
                    <?php endif; ?>
                    <p><?= $author_description ?></p>
                </div>
            </div>
            <?php
            break;
        case "comments":
            if(!$first)
                echo "<div class='block_divider'></div>";
            comments_template();
            break;
        case "footer_open":

        break;
        case "similar_records":
            echo "<div class='block_divider'></div>";
            wa_subtemplate("similar-records", null, $post);
        break;
    }
}

wa_template("frame", null, "begin");

$similars_are_printed = wa_is_page();
$in_wa_section = false;

$blocks_sequence = $settings->record__blocks_sequence;

$toend = count($blocks_sequence["sequence"]);
$first = true;

if($settings->record__img_outside): ?>
    <div class="wa-wide-image">
        <?php if($settings->record__date_in_square)
            wa_the_date_square($post->post_date); ?>
        <?php wa_the_post_thumbnail($post, "full"); ?>
    </div>
<?php endif;
echo "<div class=\"wa-section\">";

foreach($blocks_sequence["sequence"] as $block => $visible){
   $toend--;
   if($block == "similar_records") {
       if($toend == 0 && $visible)
           break;
       $similars_are_printed = true;
   }
   $context["in_wa_section"] = $in_wa_section;
   $context["first"] = $first;
   $context["last"] = $toend == 0;
   if($visible){
      if(preg_match("/^block_(\\d+)$/", $block, $block_num_match) === 1)
          echo do_shortcode(base64_decode($blocks_sequence["blocks"][intval($block_num_match[1]) - 1]));
      else
          wa_print_block($block, $context);
   }
   $first = false;
}
echo "</div>";
?>

<?php wa_template("frame", null, ["part" => "end", "similars" => !$similars_are_printed]); ?>

