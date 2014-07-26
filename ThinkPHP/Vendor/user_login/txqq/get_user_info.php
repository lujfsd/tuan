<?php
/*
 * This is only a simple demo.
 * It is a free software; you can redistribute it 
 * and/or modify it. 
 */
require_once("utils.php");

 /*
 * @brief get user info 
 * @param $appid
 * @param $appkey
 * @param $access_token
 * @param $access_token_secret
 * @param $openid
 *
 */
function get_user_info($appid, $appkey, $access_token, $access_token_secret, $openid)
{
    //get user info 的api接口，不要随便更改!!
    $url    = "http://openapi.qzone.qq.com/user/get_user_info";
    echo do_get($url, $appid, $appkey, $access_token, $access_token_secret, $openid);
}

//test

?>
