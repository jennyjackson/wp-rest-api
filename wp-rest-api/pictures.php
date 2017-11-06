<?php 
add_action( 'init', 'add_picture_to_json_api', 30 );

// 启用Rest
function add_picture_to_json_api(){
    global $wp_post_types;
    if($wp_post_types['picture']){
        $wp_post_types['picture']->show_in_rest = true;
    }
}

