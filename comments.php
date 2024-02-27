<?php

require_once("include/wa_walker_comment.php");

if(post_password_required())
    return;
if ($comments) {
?>
	<div id="comments" class="wa-post-comments">
		<div class="wa-comments-title">Комментарии</div>
        <ol class="wa-comments-list">
        <?php
        wp_list_comments([
            'walker'      => new Wa_Walker_Comment(),
            'avatar_size' => 120,
            'style'       => 'div',
        ]);
        ?>
        </ol>
        <?php wa_subtemplate("pagination", null, "comments") ?>
    </div>
    <?php
}
if (comments_open() || pings_open())
    wa_subtemplate("comments-form");
elseif (is_single() || is_page()) {
    // сообщение, что комментарии отключены
}