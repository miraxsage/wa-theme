<?php

add_action('admin_enqueue_scripts', function($hook){
    if (!in_array($hook, array('post.php', 'post-new.php')))
        return;
    wp_enqueue_script('wa-main-category', WA_JS_URI."main-category.js", array('jquery'));
});

add_action('add_meta_boxes', function(){
    add_meta_box('main_category_meta_box', "Главная рубрика", function($post) {
        wp_nonce_field(basename(__FILE__), 'main_category_meta_box_nonce');
        $wp_main_cat = get_post_meta($post->ID, 'wa_main_category', true);
        if (empty($wp_main_cat))
            $wp_main_cat = 0;
        ?>
        <select name="wa_main_category" id="wa_main_category" class="regular-text" data-main-category="<?= $wp_main_cat ?>" style="width: calc(100% - 36px);">
            <option value="0">Не выбрана...</option>
        </select>
        <br/>
        <small style="display: inline-block;margin-top: 8px;color: #757575;">Выберите основную рубрику, которая будет участвовать в определении настроек внешнего вида WebArchitect</small>
        <?php
    }, 'post', 'side', 'high');
});

add_action('save_post', function($post_id){
    if (!isset($_POST['wa_main_category']) || !wp_verify_nonce($_POST['main_category_meta_box_nonce'], basename(__FILE__)))
        return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!current_user_can('edit_post', $post_id))
        return;
    if (isset($_POST['wa_main_category']))
        update_post_meta($post_id, 'wa_main_category', sanitize_text_field($_POST['wa_main_category']));
});
