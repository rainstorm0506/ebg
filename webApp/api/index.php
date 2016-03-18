<?php
date_default_timezone_set('PRC');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: OPTIONS , POST');
header("Access-Control-Allow-Headers: x-requested-with , content-type");

$root = dirname(__FILE__);

//调试环境
#defined('YII_DEBUG') or define('YII_DEBUG' , true);
#defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL' , 3);

require_once($root . '/../../Yii/yii.php');
Yii::createWebApplication($root.'/protected/config/main.php')->run();