<?php


class Wa_Walker_Comment extends \Walker_Comment {
    var $tree_type = 'comment';
    var $db_fields = array(
        'parent' => 'comment_parent',
        'id'     => 'comment_ID'
    );

    // перед выводом дочерних комментариев
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 2; ?>
        <ol class="wa-children-comment">
    <?php }

    // end_lvl после вывода дочерних комментариев
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $GLOBALS['comment_depth'] = $depth + 2; ?>
        </ol>
    <?php }

    // start_el начало вывода самого комментария (перед выводом его дочерних)
    public function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
        $depth++;
        $GLOBALS['comment_depth'] = $depth;
        $GLOBALS['comment'] = $comment;
        global $wp;
        $settings = WaThemeSettings::get();
        ?>
        <li <?= comment_class('wa-comment') ?> id="comment-<?php comment_ID() ?>" itemprop="comment" itemscope itemtype="http://schema.org/Comment">
            <article>
                <div class="wa-comment-avatar">
                    <?= get_avatar($comment, 65, '', 'Author’s avatar') ?>
                </div>
                <header class="wa-comment-meta">
                    <cite itemprop="creator">
                        <?php if($settings->record__author_link_mode["comments"]) : ?>
                            <a rel="external nofollow ugc" href="<?php comment_author_url(); ?>"><?php comment_author(); ?></a>
                        <?php else: comment_author(); endif; ?>

                    </cite>
                    <time itemprop="datePublished" datetime="<?php comment_date('Y-m-d') ?>T<?php comment_time('H:iP') ?>"><a href="<?php echo home_url($wp->request); ?>/#comment-<?php comment_ID(); ?>"><?php comment_date('F jS Y') ?></a></time>
                </header>
                <div class="wa-comment-content" itemprop="text">
                    <?php comment_text() ?>
                </div>
                <?php if ($comment->comment_approved == '0') : ?>
                    <p class="comment-meta-item auxilary_title">Ваш комментарий ожидает модерации.</p>
                <?php endif; ?>
                <div class="wa-comment-edit-reply">
                    <?php edit_comment_link('Редактировать','',''); ?>
                    <?php comment_reply_link(array_merge( $args, array('add_below' => '', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                </div>
            </article>
    <?php }

    // end_el – завершение вывода комментария после завершения вывода дочерних
    public function end_el(&$output, $comment, $depth = 0, $args = array() ) { ?>
        </li>
    <?php }

}
?>