<?php
$settings = WaThemeSettings::get();

if(!isset($args) || !is_string($args) || preg_match("/^full|inline|footer$/", $args) !== 1)
    $args = "full";

if(empty($post = wa_get_post($post)))
    $args = "";
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

$context = compact($settings, $args, $post, $comments_count, $comments_count_capiton, $comments_link, $categories_raw, $categories, $suffix,
    $terms, $author_name, $author_link, $post_date, $post_date_attr, $post_date_mod, $views_count, $views_caption, $read_time);

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

    break;
    }
    }

    if($args == "full"): ?>

    <div class="wa-meta-icon">
		<span class="wa-article-comments">
			<span class="wa-meta-label">Комментарии</span>
			<a href="<?= $comments_link ?>"><?= $comments_count ?></a>
		</span>
        <span class="wa-article-category">
			<span class="wa-meta-label">Категории</span>
			<?= $categories ?>
		</span>
        <span class="wa-article-hashtags">
			<span class="wa-meta-label">Метки</span>
			<?= $terms ?>
		</span>
        <span class="wa-article-authorship" itemscope itemtype="https://schema.org/Person" itemprop="author">
			<span class="wa-meta-label">Автор</span>
            <?php if($settings->record_author_link_mode["meta"]) : ?>
                <a title="Смотреть все записи от <?= $author_name ?>" href="<?= $author_link ?>" rel="author" itemprop="url"><span itemprop="name"><?= $author_name ?></span> </a>
            <?php else: ?>
                <span itemprop="name"><?= $author_name ?></span>
            <?php endif; ?>
		</span>
        <span class="wa-article-published">
			<span class="wa-meta-label">Опубликовано</span>
			<time itemprop="datePublished" datetime="<?= $post_date_attr ?>"><?= $post_date ?></time>
		</span>
        <span class="wa-article-updated">
			<span class="wa-meta-label">Обновлено</span>
			<time itemprop="dateModified"><?= $post_date_mod ?></time>
		</span>
        <span class="wa-article-views">
			<span class="wa-meta-label">Просмотров</span>
			<?= $views_count ?>
		</span>
        <span class="wa-article-reading-time">
			<span class="wa-meta-label">На чтение</span>
            <?= $read_time ?>
		</span>
    </div>

    <?php elseif($args == "inline") : ?>

    <div class="wa-meta-inline">
    <span class="wa-article-comments">
        <a href="<?= $comments_link ?>"><?= $comments_count_capiton ?></a>
    </span>
        /
        <span class="wa-article-category">
			<?= $categories ?>
		</span>
        /
        <span class="wa-article-hashtags">
			<?= $terms ?>
		</span>
        /
        <span class="wa-article-authorship" itemscope itemtype="https://schema.org/Person" itemprop="author">
			От <a title="Смотреть все записи от <?= $author_name ?>" href="<?= $author_link ?>" rel="author" itemprop="url"><b itemprop="name"><?= $author_name ?></b>	</a>
		</span>
        /
        <span class="wa-article-published">
			Опубликовано: <time itemprop="datePublished" datetime="<?= $post_date_attr ?>"><?= $post_date ?></time>
		</span>
        /
        <span class="wa-article-updated">
			Обновлено: <time itemprop="dateModified"><?= $post_date_mod ?></time>
		</span>
        /
        <span class="wa-article-views"><?= $views_caption ?></span>
        /
        <span class="wa-article-reading-time"><?= $read_time ?> чтения</span>
    </div>

    <?php elseif($args == "footer"): ?>

    <div class="wa-meta-footer">
        <div class="wa-article-category">
            <span class="wa-meta-headline">Категории:</span> <?= $categories ?>
        </div>
        <div class="wa-article-hashtags">
            <span class="wa-meta-headline">Теги:</span> <?= $terms ?>
        </div>
    </div>

<?php endif; ?>
