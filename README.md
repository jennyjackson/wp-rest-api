# WordpPress rest api 定制化插件

## 原版

[https://github.com/iamxjb/wp-rest-api-for-app](https://github.com/iamxjb/wp-rest-api-for-app)

## 插件使用及注意事项见：

[https://www.watch-life.net/wordpress-weixin-app](https://www.watch-life.net/wordpress-weixin-app)

## 图片上传功能

本功能使用七牛图片上传功能

找到qiniu-upload-token.php文件，将相关信息填入即可，其他就需要小程序端配合使用了

``` javascript
function getToken()
{
  $bucket = '###########';
  $accessKey = '###########';
  $secretKey = '###########';
  $putPolicy = new Qiniu_RS_PutPolicy_WP($bucket);
  $upToken = $putPolicy->Token(null);
  $result['uptoken'] = $upToken;
  return $result;
}
```

## PC站点与小程序点赞数同步

全局搜索本项目下的『zm_like』字符串，改为自己主题存储点赞数的字段名