<?php
add_filter('rest_prepare_picture', 'custom_fields_rest_prepare_picture', 10, 3);
//获取文章的缩略图，评论数目，分类名称
//在rest api 增加显示字段
function custom_fields_rest_prepare_picture($data, $post, $request)
{
    global $wpdb;
    $_data = $data->data;
    $post_id = $post->ID;

    $old_post = $GLOBALS['post'];
    $GLOBALS['post'] = (object)$post;
    $terms = get_the_terms( $post_id , 'gallery' );
	$_data['category_name'] = $terms[0]->name;
    $next_post = get_next_post($terms[0]->term_id, '', 'gallery');
    $previous_post = get_previous_post($terms[0]->term_id, '', 'gallery');
    $_data['next_post_id'] = $next_post->ID;
    $_data['next_post_title'] = $next_post->post_title;
    $_data['previous_post_id'] = $previous_post->ID;
    $_data['previous_post_title'] = $previous_post->post_title;
    $GLOBALS['post'] = $old_post;

    $comments_count = wp_count_comments($post_id);
    $pageviews = (int) get_post_meta($post_id, 'views', true);
    $_data['pageviews'] = $pageviews;
    $_data['total_comments'] = $comments_count->total_comments;
    $like_count = (int) get_post_meta($post_id, 'zm_like', true);
    $_data['like_count'] = $like_count;
    $sql = "SELECT meta_key , (SELECT display_name from " . $wpdb->users . " WHERE user_login=substring(meta_key,2)) as avatarurl FROM " . $wpdb->postmeta . " where meta_value='like' and post_id=" . $post_id;
    $likes = $wpdb->get_results($sql);
    $avatarurls = array();
    foreach ($likes as $like) {
        $_avatarurl['avatarurl'] = $like->avatarurl;
        //$_avatarurl['openid'] = $like->meta_key;
        $avatarurls[] = $_avatarurl;
    }
    $_data['avatarurls'] = $avatarurls;
    unset($_data['excerpt']);
    unset($_data['featured_media']);
    unset($_data['format']);
    unset($_data['ping_status']);
    unset($_data['template']);
    unset($_data['type']);
    unset($_data['slug']);
    unset($_data['modified_gmt']);
    unset($_data['date_gmt']);
    unset($_data['meta']);
    unset($_data['guid']);
    unset($_data['curies']);
    $data->data = $_data;
    return $data;
}