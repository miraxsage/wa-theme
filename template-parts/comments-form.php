<?php
$settings = WaThemeSettings::get();
$user          = wp_get_current_user();
$user_identity = $user->exists() ? $user->display_name : '';
$required_sign = "*";
$commenter = wp_get_current_commenter();
$commenter_is_saved = arr_has($commenter, "comment_author", true) && arr_has($commenter, "comment_author_email", true);
if(preg_match("/<span.+?>(.+?)<\/span>/ui", wp_required_field_indicator(), $matches) === 1)
    $required_sign = $matches[1];
$args = [
    'class_container' => 'wa-comment-respond',
    'title_reply_before' => '<div class="wa-comment-reply-title">',
    'title_reply' => 'Оставьте комментарий',
    'title_reply_after' => '</div>',
    'logged_in_as' => sprintf('<p class="wa-logged-in-as">
			<a href="%s" aria-label="Вы вошли как %s. Изменить профиль.">Вы вошли как %2$s</a>. 
			<a href="%s">Выйти?</a> 
			<span class="required-field-message" aria-hidden="true">Обязательные поля помечены '.wp_required_field_indicator().'</span>
		</p>',
        get_edit_user_link(),
        $user->exists() ? $user->display_name : '',
        wp_logout_url(apply_filters('the_permalink', get_permalink($post->id), $post->id))),
    'must_log_in' => sprintf('<p class="wa-need-logged-in">
			Для отправки комментария Вам необходимо <a href="%s">авторизоваться</a>.
		</p>', wp_login_url(apply_filters('the_permalink', get_permalink($post->id), $post->id))),
    'comment_notes_before' => '<p class="wa-not-logged">
			Ваш адрес email не будет опубликован.<br>Обязательные поля помечены '.wp_required_field_indicator().'
		</p>',
    'cancel_reply_before' => '',
    'cancel_reply_after' => '',
    'cancel_reply_link' => '<span class="dashicons dashicons-undo"></span>',
    'comment_field' => sprintf('<p class="wa-comment-form">
			<label for="comment" class="screen-reader-text">Введите комментарий... %s</label>
			<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required" placeholder="Комментарий"></textarea>
		</p>', wp_required_field_indicator()),
    'fields' => [
        'author' => sprintf('<div class="wa-comment-details"><p class="wa-comment-form-author">
			<input id="author" name="author" type="text" value="%s" placeholder="Имя '.$required_sign.'" size="30" aria-required="true">
		</p>',
            esc_attr($commenter['comment_author'])),
        'email' => sprintf('<p class="wa-comment-form-email">
			<input id="email" name="email" type="text" value="%s" placeholder="Email '.$required_sign.'" size="30" aria-required="true">
		</p>',
            esc_attr($commenter['comment_author_email'])),
        'url' => sprintf('<p class="wa-comment-form-url">
			<input id="url" name="url" type="text" value="" placeholder="Сайт" size="30">
		</p></div>',
            esc_attr($commenter['comment_author_url'])),
        'cookies' => ($settings->record_comments__save_name ? sprintf('<p class="wa-comment-form-consent">
			<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" %s value="yes"> 
			<label for="wp-comment-cookies-consent">
				%s
			</label>
		</p>', $commenter_is_saved ? "checked" : "", $settings->record_comments__save_name_label ?? "Сохранить моё имя, email и адрес сайта в этом браузере для последующих моих комментариев") : "").
            ($settings->record_comments__personal_agreement ? sprintf('<p class="wa-comment-form-consent">
			<input id="wp-comment-cookies-consent" name="wp-comment-personal-agreement" type="checkbox" value="yes"> 
			<label for="wp-comment-cookies-consent">
				%s
			</label>
		</p>', $settings->record_comments__personal_agreement_label ?? "Даю согласие на обработку перональных данных") : "")
    ],
    'submit_field' => '<p class="wa-form-submit">%1$s %2$s</p>'

];
ob_start();
comment_form($args);
$comment_form = ob_get_contents();
ob_end_clean();
$comment_form = str_replace('id="cancel-comment-reply-link', 'title="Отменить ответ для '.$user_identity.'" id="cancel-comment-reply-link', $comment_form);
echo $comment_form;
