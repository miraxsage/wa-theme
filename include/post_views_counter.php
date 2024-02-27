<?php
// Подсчет количества посещений страниц
add_action( 'wp_head', 'wa_postviews' );

/**
 * @param array $args
 *
 * @return null
 */
function wa_postviews( $args = [] ){
    global $user_ID, $post, $wpdb;

    if( ! $post || ! is_singular() )
        return;

    $rg = (object) wp_parse_args( $args, [
        // Ключ мета поля поста, куда будет записываться количество просмотров.
        'meta_key' => 'views',
        // Чьи посещения считать? 0 - Всех. 1 - Только гостей. 2 - Только зарегистрированных пользователей.
        'who_count' => 0,
        // Исключить ботов, роботов? 0 - нет, пусть тоже считаются. 1 - да, исключить из подсчета.
        'exclude_bots' => true,
    ] );
    $do_count = false;
    switch( $rg->who_count ){

        case 0:
            $do_count = true;
            break;
        case 1:
            if( ! $user_ID )
                $do_count = true;
            break;
        case 2:
            if( $user_ID )
                $do_count = true;
            break;
    }

    if( $do_count && $rg->exclude_bots ){

        $notbot = 'Mozilla|Opera'; // Chrome|Safari|Firefox|Netscape - все равны Mozilla
        $bot = 'Bot/|robot|Slurp/|yahoo';
        if(
            ! preg_match( "/$notbot/i", $_SERVER['HTTP_USER_AGENT'] ) ||
            preg_match( "~$bot~i", $_SERVER['HTTP_USER_AGENT'] )
        ){
            $do_count = false;
        }

    }

    if( $do_count ){

        $up = $wpdb->query( $wpdb->prepare(
            "UPDATE $wpdb->postmeta SET meta_value = (meta_value + 1) WHERE post_id = %d AND meta_key = %s",
            $post->ID, $rg->meta_key
        ) );

        if( ! $up ){
            add_post_meta( $post->ID, $rg->meta_key, 1, true );
        }

        wp_cache_delete( $post->ID, 'post_meta' );
    }

}