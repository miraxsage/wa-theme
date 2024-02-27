<?php
add_action('wp_enqueue_scripts', function($hook){
    $queried_obj = wa_queried_object();
    if($queried_obj instanceof WP_Post)
        wp_enqueue_script('wa-social-buttons', WA_JS_URI."social-buttons.js", array('jquery'));
});