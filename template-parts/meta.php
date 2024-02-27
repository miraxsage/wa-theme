<?php
$settings = WaThemeSettings::get();

$mode = "full";
$sequence = $settings->record_meta__blocks_sequence;
$target = "record";

if(isset($args)){
    if(is_string($args)) {
        if (preg_match("/^full|inline|footer$/", $args) === 1)
            $mode = $args;
    }
    elseif(is_array($args)){
        if(array_key_exists("mode", $args) && preg_match("/^full|inline|footer$/", $args["mode"]) === 1)
            $mode = $args["mode"];
        if(array_key_exists("target", $args)) {
            $target = $args["target"];
            if($target == "archive") {
                $sequence = $settings->archive_meta__blocks_sequence;
                if(!array_key_exists("mode", $args))
                    $mode = $settings->archive_meta__use_icons ? "full" : "inline";
            }
            else
                $target = "record";
        }

    }

}

//if(empty($post = wa_get_post($post)))
//    $args = "";

$comments_count = floatval($post->comments_count);
$comments_count_capiton = $comments_count." комментари".declention($comments_count, "ев", "й", "я");
$comments_link = get_comments_link($post->ID);
$categories_raw = wp_get_post_categories($post->ID, ["fields" => "id=>name"]);
$categories = $suffix = "";
foreach($categories_raw as $catid => $catname){
    $categories .= $suffix.sprintf('<a href="%s" rel="category tag">%s</a>', get_category_link($catid), $catname);
    $suffix = ", ";
}
if(empty($categories))
    $categories = "Без категории";
$terms_raw = wp_get_post_terms($post->ID);
$terms = $suffix = "";
foreach ($terms_raw as $term){
    $terms .= $suffix.sprintf('<a href="%s" rel="tag">%s</a>', get_term_link($term), $term->name);
    $suffix = ", ";
}
if(empty($terms))
    $terms = "Без тегов";

$author_name = get_the_author_meta('display_name', $post->post_author);
$author_link = get_author_posts_url($post->post_author);
$post_date = strtotime($post->post_date);
$post_date_attr = date("Y-m-d", $post_date);
$post_date = date("d.m.Y", $post_date);
$post_date_mod = strtotime($post->post_modified);
$post_date_mod = date("d.m.Y", $post_date_mod);
$views_count = intval(get_post_meta($post->ID, 'views', true));
$views_caption = $views_count." просмотр".declention($views_count, "ов", "", "а");
$read_time = reading_time($post);

$context = compact("settings", "mode", "target", "post", "comments_count", "comments_count_capiton", "comments_link",
    "categories_raw", "categories", "suffix", "terms", "author_name", "author_link", "post_date", "post_date_attr", "post_date_mod",
    "views_count", "views_caption", "read_time");

if(!function_exists("wa_print_meta")){
    function wa_print_meta($meta, $context, $mode = "full"){
        extract($context);
        $full = $mode == "full";
        switch ($meta){
            case "comments":
                if($full): ?>
                    <span class="wa-article-comments">
                        <span class="wa-meta-label">Комментарии</span>
                        <a href="<?= $comments_link ?>"><?= $comments_count ?></a>
                    </span>
                <?php else: ?>
                    <span class="wa-article-comments">
                        <a href="<?= $comments_link ?>"><?= $comments_count_capiton ?></a>
                    </span>
                <?php endif;
                break;
            case "categories":
                if($full): ?>
                    <span class="wa-article-category">
                        <span class="wa-meta-label">Категории</span>
                        <?= $categories ?>
                    </span>
                <?php else: ?>
                    <span class="wa-article-category">
                        <?= $categories ?>
                    </span>
                <?php endif;
                break;
            case "marks":
                if($full): ?>
                    <span class="wa-article-hashtags">
                        <span class="wa-meta-label">Метки</span>
                        <?= $terms ?>
                    </span>
                <?php else: ?>
                    <span class="wa-article-hashtags">
                        <?= $terms ?>
                    </span>
                <?php endif;
                break;
            case "author":
                if($full): ?>
                    <span class="wa-article-authorship" itemprop="author"<?= schema_item("about_author", " ") ?>>
                        <span class="wa-meta-label">Автор</span>
                        <?php if($target == "archive" ? $settings->archive__author_link : $settings->record__author_link_mode["meta"]) : ?>
                            <a title="Смотреть все записи от <?= $author_name ?>" href="<?= $author_link ?>" rel="author" itemprop="url"><span itemprop="name"><?= $author_name ?></span> </a>
                        <?php else: ?>
                            <span itemprop="name"><?= $author_name ?></span>
                        <?php endif; ?>
                    </span>
                <?php else: ?>
                    <span class="wa-article-authorship" itemprop="author"<?= schema_item("about_author", " ") ?>>
                        От <a title="Смотреть все записи от <?= $author_name ?>" href="<?= $author_link ?>" rel="author" itemprop="url"><b itemprop="name"><?= $author_name ?></b>	</a>
                    </span>
                <?php endif;
                break;
            case "created":
                if($full): ?>
                    <span class="wa-article-published">
                        <span class="wa-meta-label">Опубликовано</span>
                        <time itemprop="datePublished" datetime="<?= $post_date_attr ?>"><?= $post_date ?></time>
                    </span>
                <?php else: ?>
                    <span class="wa-article-published">
                        Опубликовано: <time itemprop="datePublished" datetime="<?= $post_date_attr ?>"><?= $post_date ?></time>
                    </span>
                <?php endif;
                break;
            case "updated":
                if($full): ?>
                    <span class="wa-article-updated">
                        <span class="wa-meta-label">Обновлено</span>
                        <time itemprop="dateModified"><?= $post_date_mod ?></time>
                    </span>
                <?php else: ?>
                    <span class="wa-article-updated">
                        Обновлено: <time itemprop="dateModified"><?= $post_date_mod ?></time>
                    </span>
                <?php endif;
                break;
            case "views":
                if($full): ?>
                    <span class="wa-article-views">
                        <span class="wa-meta-label">Просмотров</span>
                        <?= $views_count ?>
                    </span>
                <?php else: ?>
                    <span class="wa-article-views"><?= $views_caption ?></span>
                <?php endif;
                break;
            case "read_time":
                if($full): ?>
                    <span class="wa-article-reading-time">
                        <span class="wa-meta-label">На чтение</span>
                        <?= $read_time ?>
                    </span>
                <?php else: ?>
                    <span class="wa-article-reading-time"><?= $read_time ?> чтения</span>
                <?php endif;
                break;
        }
    }
}
if($mode == "full" || $mode == "inline"):

    if($mode == "full")
        echo "<div class=\"wa-meta-icon\">";
    else
        echo "<div class=\"wa-meta-inline\">";
    $first = true;
    foreach($sequence as $meta => $visible){
        if(!$first && $mode != "full" && $visible)
            echo "/";
        if($visible)
            wa_print_meta($meta, $context, $mode);
        if($visible)
            $first = false;
    }
    echo "</div>";

elseif($mode == "footer"): ?>

    <div class="wa-meta-footer">
        <div class="wa-article-category">
            <span class="wa-meta-headline">Категории:</span> <?= $categories ?>
        </div>
        <div class="wa-article-hashtags">
            <span class="wa-meta-headline">Теги:</span> <?= $terms ?>
        </div>
    </div>

<?php endif; ?>
