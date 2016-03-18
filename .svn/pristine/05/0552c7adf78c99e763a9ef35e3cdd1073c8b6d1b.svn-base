<?php
date_default_timezone_set('PRC');
header('Content-type: text/html; charset=utf-8');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0' , false);
header('Pragma: no-cache');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: OPTIONS , POST');
header("Access-Control-Allow-Headers: x-requested-with , content-type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
	exit;

//$e					= DIRECTORY_SEPARATOR;
$e					= '/';
$targetFolder		= "{$e}upload_temp{$e}".date('Y').$e.date('m-d');
$targetPath			= dirname(__FILE__) . $targetFolder;
$fileTypes			= array('jpg','jpeg','gif','png','bmp');

//pc图片处理
if (!empty($_FILES['file']))
{
	$fileParts = pathinfo($_FILES['file']['name']);
	if (in_array(strtolower($fileParts['extension']) , $fileTypes))
	{
		$targetFile		= $e.sprintf('%u',crc32($_FILES['file']['name'])).mt_rand().microtime(true).'.'.$fileParts['extension'];
		$src			= $targetFolder.$targetFile;
		$targetFile		= rtrim($targetPath , $e).$targetFile;
		
		$width			= empty($_POST['width']) ? 0 : (int)$_POST['width'];
		$height			= empty($_POST['height']) ? 0 : (int)$_POST['height'];
		if ($width && $height)
		{
			if ($size = @getimagesize($_FILES['file']['tmp_name']))
			{
				if ($size[0] != $width || $size[1] != $height)
				{
					echo json_encode(array('error'=>5 , 'message'=>'图片尺寸错误'));
					exit;
				}
			}else{
				echo json_encode(array('error'=>4 , 'message'=>'获取不到图片或者不是一个图片'));
				exit;
			}
		}
		
		@mkdir($targetPath , 0755 , true);
		if (@move_uploaded_file($_FILES['file']['tmp_name'] , $targetFile))
		{
			echo json_encode(array('error'=>0 , 'src'=>$src));
			exit;
		}
	}else{
		echo json_encode(array('error'=>1 , 'message'=>'上传的不是图片文件'));
		exit;
	}
	echo json_encode(array('error'=>2 , 'message'=>'上传未知错误.'));

//app图像
}elseif (!empty($_FILES['appFile'])){
	if (empty($_FILES['appFile']['name']) || !is_array($_FILES['appFile']['name']) || !(array_filter($_FILES['appFile']['name'])))
	{
		echo json_encode(array('error'=>201 , 'message'=>'请选择你要上传的图片组!','data'=>array()));
		exit;
	}

	$width		= empty($_POST['width']) ? 0 : (int)$_POST['width'];
	$height		= empty($_POST['height']) ? 0 : (int)$_POST['height'];

	$retx = array();
	$_k = -1;
	foreach ($_FILES['appFile']['name'] as $key => $pic)
	{
		$_k++;
		$fileParts = pathinfo($pic);
		if (!empty($fileParts['extension']) && in_array(strtolower($fileParts['extension']) , $fileTypes))
		{
			$targetFile		= $e.sprintf('%u',crc32($pic)).mt_rand().microtime(true).'.'.$fileParts['extension'];
			$src			= $targetFolder.$targetFile;
			$targetFile		= rtrim($targetPath , $e).$targetFile;
			
			if (empty($_FILES['appFile']['tmp_name'][$key]))
			{
				//空地址
				$retx[$_k] = array('key'=>$key,'code'=>2,'src'=>'');
			}else{
				if ($width && $height)
				{
					if ($size = @getimagesize($_FILES['appFile']['tmp_name'][$key]))
					{
						if ($size[0] != $width || $size[1] != $height)
						{
							//图片尺寸错误
							$retx[$_k] = array('key'=>$key,'code'=>3,'src'=>'');
						}
					}else{
						//获取不到图片或者不是一个图片
						$retx[$_k] = array('key'=>$key,'code'=>4,'src'=>'');
					}
				}
				
				@mkdir($targetPath , 0755 , true);
				if (@move_uploaded_file($_FILES['appFile']['tmp_name'][$key] , $targetFile))
					$retx[$_k] = array('key'=>$key,'code'=>0 , 'src'=>$src);
			}
		}else{
			//上传的不是图片文件
			$retx[$_k] = array('key'=>$key,'code'=>1,'src'=>'');
		}
	}

	if ($retx)
	{
		echo json_encode(array('code'=>0 , 'message'=>'' , 'data'=>$retx));
	}else{
		echo json_encode(array('code'=>202 , 'message'=>'未知错误!','data'=>array()));
	}
	exit;
}else{
	if (!($data = empty($GLOBALS['HTTP_RAW_POST_DATA'])?file_get_contents('php://input'):$GLOBALS['HTTP_RAW_POST_DATA']))
	{
		echo json_encode(array('code'=>100 , 'message'=>'不存在上传的数据.'));
		exit;
	}
	
	$name = md5(microtime(true) .'.'. mt_rand() .'.'. mt_rand()) . '.jpg';
	@mkdir($targetFolder , 0777 , true);
	#echo $targetFolder.'/'.$name;exit;
	if (file_put_contents(($targetFolder.'/'.$name) , $data) === false)
	{
		echo json_encode(array('code'=>101 , 'message'=>'图片保存失败.'));
		exit;
	}else{
		echo json_encode(array('code'=>0 , 'data'=>array('src'=>$targetFolder.'/'.$name)));
		exit;
	}
	echo json_encode(array('code'=>102 , 'message'=>'找不到上传的文件'));	
}