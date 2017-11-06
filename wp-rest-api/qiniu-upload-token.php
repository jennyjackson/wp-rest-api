<?php

add_action('rest_api_init', function () {
  register_rest_route('qipalin/v1', '/qiniu', array('methods' => 'GET', 'callback' => 'getQiniuToken'));
});

function getQiniuToken($data)
{
  $data = getToken();
  if (empty($data)) {
    return new WP_Error('noposts', 'noposts', array('status' => 404));
  }
  $response = new WP_REST_Response($data);
  $response->set_status(201);
  return $response;
}

function getToken()
{
  $bucket = '';
  $accessKey = '';
  $secretKey = '';
  $putPolicy = new Qiniu_RS_PutPolicy_WP($bucket);
  $upToken = $putPolicy->Token(null);
  $result['uptoken'] = $upToken;
  return $result;
}


