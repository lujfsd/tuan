<?php
/*
 * 获取requestToken,并且跳转到用户认证页面
 */
session_start();
include_once('lib/Hao360Auth.php');

$callback = "http://".$_SERVER['HTTP_HOST'].'/index.php?m=360';

$authSvc      = new Hao360Auth();
$token        = $authSvc->getRequestToken();
//var_dump($token);
if($token["oauth_token"])
{
    $_SESSION['request_token'] = $token;
    $authorizeUrl = $authSvc->getAuthorizeURL( $token['oauth_token'] , $callback );
    header("Location:$authorizeUrl");
}
else
{
    //错误了,可以定义跳转到对应错误页面
    echo " AUTH FAIL ! \r\n";
    echo $token["msg"];
}

