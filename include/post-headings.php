<?php
add_action('wp_enqueue_scripts', function($hook){
    $queried_obj = wa_queried_object();
    if($queried_obj instanceof WP_Post)
        wp_enqueue_script('wa-post-headings', WA_JS_URI."post-headings.js", array('jquery'));
});