При переносе темы, модификации и прочих может возникнуть необходимость обновить PHP-зависимость carbon-fields из папки vendor.
Так вот в нее были внесены некоторые изменения, помогающие сохранять активную вкладку после сохранения настроек, для этого нужно:
в файле vendor\carbon-fields\vendor\htmlburger\carbon-fields\core\Container\Theme_Options_Container.php
в процедуре public function save( $user_data = null )
вместо:
if ( ! headers_sent() ) {
    wp_redirect( add_query_arg( array( 'settings-updated' => 'true' ) ) );
}
добавить:
if ( ! headers_sent() ) {
    $args = ['settings-updated' => 'true'];
    foreach($_REQUEST as $key => $val){
        if(str_starts_with($key, "wa-carbon-active-tab__"))
            $args[$key] = $val;
    }
    wp_redirect( add_query_arg( $args ) );
}