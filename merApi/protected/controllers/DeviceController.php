<?php
/**
 * 设备 - 控制器
 * 此层的错误编码是 100 - 199
 * 
 * @author simon
 */
class DeviceController extends ApiController
{
	//提交设备信息 (第一次打开)
	public function actionInit()
	{
		$pars = array(
			'model'				=> (string)$this->getPost('m'),		#手机型号
			'versions'			=> (string)$this->getPost('v'),		#手机版本号
			'android_vers'		=> (string)$this->getPost('av'),	#android版本
			'system_cpu'		=> (string)$this->getPost('sc'),	#CPU
			'system_ram'		=> (int)$this->getPost('sr'),		#内存 (MB)
			'system_hd'			=> (int)$this->getPost('shd'),		#磁盘空间
			'available_hd'		=> (int)$this->getPost('ahd'),		#可用的空间
			'system_bb'			=> (string)$this->getPost('bb'),	#基带
			'system_core'		=> (string)$this->getPost('c'),		#内核
			'network'			=> (int)$this->getPost('n'),		#手机号码运营商 , 0=未知 , 1=电信 , 2=移动 , 3=联通
			'imei'				=> (string)$this->getPost('i'),		#IMEI
			'mac'				=> (string)$this->getPost('mac'),	#MAC地址
			'is_blue'			=> (int)$this->getPost('b'),		#蓝牙 , 0=不支持 , 1=支持
			'is_front_camera'	=> (int)$this->getPost('fc'),		#前置摄像头 , 0=不支持 , 1=支持
			'is_bark_camera'	=> (int)$this->getPost('bc'),		#后置摄像头 , 0=不支持 , 1=支持
			'app_post_time'		=> (int)$this->getPost('apt'),		#APP抛数据的时间
		);
		
		$form = ClassLoad::Only('DeviceForm');/* @var $form DeviceForm */
		$form->attributes = $pars;
		if ($form->validate())
		{
			$model = ClassLoad::Only('Device');/* @var $model Device */
			if (!$device = $model->getDevByImei($pars['imei']))
			{
				if (!$model->writeDevice($pars))
					$this->jsonOutput(2 , '数据写入错误,请联系我们.');
			}
			
			$session = Yii::app()->session;
			$session->open();
			$this->jsonOutput(0 , array(
				'S_APISID'			=> $session->getSessionID(),
				'service_time'		=> time(),
				'imgDomain'			=> Yii::app()->params['imgDomain'],
				'imgUploadSrc'		=> Yii::app()->params['imgUploadSrc'],
				'fileUploadSrc'		=> Yii::app()->params['fileUploadSrc'],
			));
		}
		$this->jsonOutput(1 , $this->getFormError($form));
	}
}