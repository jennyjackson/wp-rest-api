<?php
//获取本站本周最受欢迎的top20文章
add_action('rest_api_init', function () {
    register_rest_route('qipalin/v1', '/hotpostthisweek', array('methods' => 'GET', 'callback' => 'getTopHotPostsThisWeek'));
});
function getTopHotPostsThisWeek($data)
{
    $data = get_mostcommented_thisweek_json(20);
    if (empty($data)) {
        return new WP_Error('noposts', 'noposts', array('status' => 404));
    }
    // Create the response object
    $response = new WP_REST_Response($data);
    // Add a custom status code
    $response->set_status(201);
    // Add a custom header
    //$response->header( 'Location', 'https://www.watch-life.net' );
    return $response;
}
// Get Top Commented Posts  this week 获取本周评论最多的文章
function get_mostcommented_thisweek_json($limit = 20)
{
    global $wpdb, $post, $tableposts, $tablecomments, $time_difference, $post;
    //获取今天日期时间
    $tomorrow = date("Y-m-d", strtotime("+1 day"));
    //本周第一天
    $fristday = this_monday(0, false);
    $sql = "SELECT  " . $wpdb->posts . ".ID as ID, post_title, post_name,post_content,post_date, COUNT(" . $wpdb->comments . ".comment_post_ID) AS 'comment_total' FROM " . $wpdb->posts . " LEFT JOIN " . $wpdb->comments . " ON " . $wpdb->posts . ".ID = " . $wpdb->comments . ".comment_post_ID WHERE comment_approved = '1' AND post_date BETWEEN '" . $fristday . "' AND '" . $tomorrow . "' AND post_status = 'publish' AND post_password = '' GROUP BY " . $wpdb->comments . ".comment_post_ID ORDER  BY comment_total DESC LIMIT " . $limit;
    $mostcommenteds = $wpdb->get_results($sql);
    $posts = array();
    foreach ($mostcommenteds as $post) {
        $post_id = (int) $post->ID;
        $post_title = stripslashes($post->post_title);
        $comment_total = (int) $post->comment_total;
        $post_date = $post->post_date;
        $post_permalink = get_permalink($post->ID);
        $_data["post_id"] = $post_id;
        $_data["post_title"] = $post_title;
        $_data["total_comments"] = $comment_total;
        $_data["date"] = $post_date;
        $_data["post_permalink"] = $post_permalink;
        $_data['pageviews'] = (int) get_post_meta($post_id, 'views', true);
        $_data['like_count'] = (int) get_post_meta($post_id, 'zm_like', true);
        $images = getPostImages($post->post_content, $post_id);
        if ($images['content_first_image']) {
            $_data['thumbnail'] = $images['content_first_image'];
        } else {
            $_data['thumbnail'] = $images['post_thumbnail_image'];
        }
        $posts[] = $_data;
    }
    return $posts;
}
//获取本站本月最受欢迎的top20文章
add_action('rest_api_init', function () {
    register_rest_route('qipalin/v1', '/hotpostthismonth', array('methods' => 'GET', 'callback' => 'getTopHotPostsThisMonth'));
});
function getTopHotPostsThisMonth($data)
{
    $data = get_mostcommented_thismonth_json(20);
    if (empty($data)) {
        return new WP_Error('noposts', 'noposts', array('status' => 404));
    }
    // Create the response object
    $response = new WP_REST_Response($data);
    // Add a custom status code
    $response->set_status(201);
    // Add a custom header
    //$response->header( 'Location', 'https://www.watch-life.net' );
    return $response;
}
// Get Top Commented Posts  this month 获取本月评论最多的文章
function get_mostcommented_thismonth_json($limit = 20)
{
    global $wpdb, $post, $tableposts, $tablecomments, $time_difference, $post;
    //获取今天日期时间
    $tomorrow = date("Y-m-d", strtotime("+1 day"));
    //本月第一天
    $fristday = date('Y-m-01', strtotime(date("Y-m-d")));
    $sql = "SELECT  " . $wpdb->posts . ".ID as ID, post_title, post_name,post_content,post_date, COUNT(" . $wpdb->comments . ".comment_post_ID) AS 'comment_total' FROM " . $wpdb->posts . " LEFT JOIN " . $wpdb->comments . " ON " . $wpdb->posts . ".ID = " . $wpdb->comments . ".comment_post_ID WHERE comment_approved = '1' AND post_date BETWEEN '" . $fristday . "' AND '" . $tomorrow . "' AND post_status = 'publish' AND post_password = '' GROUP BY " . $wpdb->comments . ".comment_post_ID ORDER  BY comment_total DESC LIMIT " . $limit;
    $mostcommenteds = $wpdb->get_results($sql);
    $posts = array();
    foreach ($mostcommenteds as $post) {
        $post_id = (int) $post->ID;
        $post_title = stripslashes($post->post_title);
        $comment_total = (int) $post->comment_total;
        $post_date = $post->post_date;
        $post_permalink = get_permalink($post->ID);
        $_data["post_id"] = $post_id;
        $_data["post_title"] = $post_title;
        $_data["total_comments"] = $comment_total;
        $_data["date"] = $post_date;
        $_data["post_permalink"] = $post_permalink;
        // begin主题专用
        $_data['thumbnail'] = (string) get_post_meta($post_id, 'thumbnail', true);
        $_data['pageviews'] = (int) get_post_meta($post_id, 'views', true);
        $_data['like_count'] = (int) get_post_meta($post_id, 'zm_like', true);
        $images = getPostImages($post->post_content, $post_id);
        if ($images['content_first_image']) {
            $_data['thumbnail'] = $images['content_first_image'];
        } else {
            $_data['thumbnail'] = $images['post_thumbnail_image'];
        }
        $posts[] = $_data;
    }
    return $posts;
}
add_action('rest_api_init', function () {
    register_rest_route('qipalin/v1', '/hotpost', array('methods' => 'GET', 'callback' => 'getTopHotPosts'));
});
//获取本站最受欢迎的top10文章
function getTopHotPosts($data)
{
    $data = get_mostcommented_json(20);
    if (empty($data)) {
        return new WP_Error('noposts', 'noposts', array('status' => 404));
    }
    // Create the response object
    $response = new WP_REST_Response($data);
    // Add a custom status code
    $response->set_status(201);
    // Add a custom header
    //$response->header( 'Location', 'https://www.watch-life.net' );
    return $response;
}
function get_mostcommented_json($limit = 20)
{
    global $wpdb, $post, $tableposts, $tablecomments, $time_difference, $post;
    $sql = "SELECT  " . $wpdb->posts . ".ID as ID, post_title, post_name, post_content,post_date, COUNT(" . $wpdb->comments . ".comment_post_ID) AS 'comment_total' FROM " . $wpdb->posts . " LEFT JOIN " . $wpdb->comments . " ON " . $wpdb->posts . ".ID = " . $wpdb->comments . ".comment_post_ID WHERE comment_approved = '1' AND post_date < '" . date("Y-m-d H:i:s", time() + $time_difference * 3600) . "' AND post_status = 'publish' AND post_password = '' GROUP BY " . $wpdb->comments . ".comment_post_ID ORDER  BY comment_total DESC LIMIT " . $limit;
    $mostcommenteds = $wpdb->get_results($sql);
    $posts = array();
    foreach ($mostcommenteds as $post) {
        $post_id = (int) $post->ID;
        $post_title = stripslashes($post->post_title);
        $comment_total = (int) $post->comment_total;
        $post_date = $post->post_date;
        $post_permalink = get_permalink($post->ID);
        $_data["post_id"] = $post_id;
        $_data["post_title"] = $post_title;
        $_data["total_comments"] = $comment_total;
        $_data["date"] = $post_date;
        $_data["post_permalink"] = $post_permalink;
        // begin主题专用
        $_data['thumbnail'] = (string) get_post_meta($post_id, 'thumbnail', true);
        $_data['pageviews'] = (int) get_post_meta($post_id, 'views', true);
        $_data['like_count'] = (int) get_post_meta($post_id, 'zm_like', true);
        $images = getPostImages($post->post_content, $post_id);
        if ($images['content_first_image']) {
            $_data['thumbnail'] = $images['content_first_image'];
        } else {
            $_data['thumbnail'] = $images['post_thumbnail_image'];
        }
        $posts[] = $_data;
    }
    return $posts;
}

//这个星期的星期一 
// @$timestamp ，某个星期的某一个时间戳，默认为当前时间 
// @is_return_timestamp ,是否返回时间戳，否则返回时间格式 
function this_monday($timestamp=0,$is_return_timestamp=true){ 
    static $cache ; 
    $id = $timestamp.$is_return_timestamp; 
    if(!isset($cache[$id])){ 
        if(!$timestamp) $timestamp = time(); 
        $monday_date = date('Y-m-d', $timestamp-86400*date('w',$timestamp)+(date('w',$timestamp)>0?86400:-/*6*86400*/518400)); 
        if($is_return_timestamp){ 
            $cache[$id] = strtotime($monday_date); 
        }else{ 
            $cache[$id] = $monday_date; 
        } 
    } 
    return $cache[$id]; 
} 