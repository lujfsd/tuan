<?php
/*
 * This is only a simple demo.
 * It is a free software; you can redistribute it 
 * and/or modify it. 
 */
require_once("utils.php");

/**
 * @brief get a access token 
 *        rfc1738 urlencode
 * @param $appid
 * @param $appkey
 * @param $request_token
 * @param $request_token_secret
 * @param $vericode
 *
 * @return a string, as follows:
 *      oauth_token=xxx&oauth_token_secret=xxx&openid=xxx&oauth_signature=xxx&oauth_vericode=xxx&timestamp=xxx
 */
function get_access_token($appid, $appkey, $request_token, $request_token_secret, $vericode)
{
    //获取access token接口，不要随便更改!!
    $url    = "http://openapi.qzone.qq.com/oauth/qzoneoauth_access_token?";
    //构造签名串.源串:方法[GET|POST]&uri&参数按照字母升序排列
    $sigstr = "GET"."&".rawurlencode("http://openapi.qzone.qq.com/oauth/qzoneoauth_access_token")."&";

    //必要参数，不要随便更改!!
    $params = array();
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = time();
    $params["oauth_nonce"]            = mt_rand();
    $params["oauth_consumer_key"]     = $appid;
    $params["oauth_token"]            = $request_token;
    $params["oauth_vericode"]         = $vericode;

    //对参数按照字母升序做序列化
    $normalized_str = get_normalized_string($params);
    $sigstr        .= rawurlencode($normalized_str);

    //echo "sigstr = $sigstr";

    //签名,确保php版本支持hash_hmac函数
    $key = $appkey."&".$request_token_secret;
    $signature = get_signature($sigstr, $key);
    //构造请求url
    $url      .= $normalized_str."&"."oauth_signature=".rawurlencode($signature);

    return file_get_contents($url);
}


?>
