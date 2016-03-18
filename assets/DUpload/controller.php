<?php
//header('Access-Control-Allow-Origin: http://*.baidu.com'); //设置http://*.baidu.com允许跨域访问
date_default_timezone_set('PRC');

// header('Content-type: text/html; charset=utf-8');
// header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
// header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
// header('Cache-Control: no-store, no-cache, must-revalidate');
// header('Cache-Control: post-check=0, pre-check=0' , false);
// header('Pragma: no-cache');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With');

$CONFIG = include('config.php');
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'config':
        $result =  json_encode($CONFIG);
        break;

    /* 上传图片 */
    case 'uploadimage':
    /* 上传涂鸦 */
    case 'uploadscrawl':
    /* 上传视频 */
    case 'uploadvideo':
    /* 上传文件 */
    case 'uploadfile':
        $result = include("action_upload.php");
        break;

    /* 列出图片 */
    case 'listimage':
        $result = include("action_list.php");
        break;
    /* 列出文件 */
    case 'listfile':
        $result = include("action_list.php");
        break;

    /* 抓取远程文件 */
    case 'catchimage':
        $result = include("action_crawler.php");
        break;

    default:
        $result = json_encode(array(
            'state'=> '请求地址出错'
        ));
        break;
}

/* 输出结果 */
if (isset($_GET["callback"])) {
    echo $_GET["callback"] . '(' . $result . ')';
} else {
    echo $result;
}