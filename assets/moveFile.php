<?php
date_default_timezone_set('PRC');
/**
 * 图片服务器上 文件的移动
 * @author		涂先锋
 * 
 * @param		$_POST['old']		原文件
 * @param		$_POST['new']		新文件地址
 * @param		$_POST['del']		是否删除原文件
 * 
 * @return		{"code":(错误码 , 非0即错误) , "message":"错误信息"}
 */
$old = empty($_POST['old']) ? '' : trim((string)$_POST['old']);
$new = empty($_POST['new']) ? '' : trim((string)$_POST['new']);
$del = !empty($_POST['del']);

$returned = array('code' => 0 , 'message' => '');
if ($old == $new)
	exit(json_encode($returned));

if (!$old || !$new)
{
	$returned['code'] = 1;
	$returned['message'] = '参数错误!';
	exit(json_encode($returned));
}

$old = substr($old , 0 , 1) === '/' ? ('.'.$old) : $old;
$new = substr($new , 0 , 1) === '/' ? ('.'.$new) : $new;

if (!is_file($old))
{
	$returned['code'] = 1;
	$returned['message'] = '原始文件不正常!';
	exit(json_encode($returned));
}

$parts = pathinfo($new);
if (is_array($parts) && !empty($parts['dirname']))
{
	@mkdir($parts['dirname'] , 0777 , true);
	if (!copy($old , $new))
	{
		$returned['code'] = 2;
		$returned['message'] = '文件复制失败!';
	}
	
	if ($del)
		@unlink($old);
	
}else{
	$returned['code'] = 3;
	$returned['message'] = '新文件地址错误!';
}
exit(json_encode($returned));
