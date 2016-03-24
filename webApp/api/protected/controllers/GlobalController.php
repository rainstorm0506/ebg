<?php
/**
 * 全局公共模块
 * 
 * @author simon
 */
class GlobalController extends WebApiController
{
	# 短信验证码过期时间 , 5分钟
	const SMSEXPIRE = 300;
	
	private $sessKey = array(
		1	=> 'sign',		#注册
		2	=> 'find',		#找回密码
		3	=> 'com_user'	#企业用户修改电话
	);
	
	public function actionToken()
	{
		$session = Yii::app()->session;
		if (empty($session['uHeader']['Xtoken']))
		{
			$this->jsonOutput(0);
		}else{
			$this->jsonOutput(0 , '' , false);
		}
	}
	
	/**
	 * 全局公共模块 - 获得图片验证码
	 * 
	 * @param		int		type		1=注册 , 2=找回密码 , 3=企业用户修改电话
	 */
	public function actionGetImgCode()
	{
		$captcha							= new VerifCodeIntAction($this , 'captcha');
		
		$type								= (int)$this->getQuery('type');
		$type								= isset($this->sessKey[$type]) ? $this->sessKey[$type] : 'null';
		$code								= $captcha->getCodes();
		$session							= isset(Yii::app()->session['imgCode']) ? (array)Yii::app()->session['imgCode'] : array();
		$session[$type]['code']				= $code;
		Yii::app()->session['imgCode']		= $session;
		
		$captcha->backColor					= 0xFFFFFF;
		$captcha->minLength					= 6;
		$captcha->maxLength					= 6;
		$captcha->height					= 40;
		$captcha->width						= 126;
		$captcha->rendCode($code);
	}
	
	/**
	 * 全局公共模块 - 异步验证图片验证码
	 *
	 * @param		int		type		1=注册 , 2=找回密码 , 3=企业用户修改电话
	 * @param		int		code		图片验证码
	 */
	public function actionViferyImgCode()
	{
		$form			= ClassLoad::Only('GlobalForm');/* @var $form GlobalForm */
		$form->type		= (int)$this->getPost('type');
		$form->code		= (int)$this->getPost('code');
		$session		= Yii::app()->session;
		$type			= isset($this->sessKey[$form->type]) ? $this->sessKey[$form->type] : 'null';
		
		if ($form->viferyImgCode(empty($session['imgCode'][$type]) ? array() : $session['imgCode'][$type]))
			$this->jsonOutput(0);
		
		$this->jsonOutput(10 , $this->getFormError($form));
	}
	
	/**
	 * 全局公共模块 - 发送手机短信码
	 *
	 * @param		int			type		1=注册 , 2=找回密码 , 3=企业用户修改电话
	 * @param		bigint		phone		手机号码
	 */
	public function actionSendSmsCode()
	{
		$form			= ClassLoad::Only('GlobalForm');/* @var $form GlobalForm */
		$form->type		= (int)$this->getPost('type');
		$form->phone	= trim((string)$this->getPost('phone'));
		
		if ($form->viferySendSmsCode())
		{
			$verCode	= mt_rand(100000 , 999999);
			$sessKey	= $this->sessKey[$form->type];
			$time		= time();
			
			#------------------------------------------ 测试开始  --------------------------------------------
			if ((int)$this->getPost('test') === 1)
			{
				$session							= isset(Yii::app()->session['smsCode']) ? (array)Yii::app()->session['smsCode'] : array();
				$session[$sessKey]					= array('phone'=>$form->phone , 'verCode'=>$verCode , 'sendTime'=>$time , 'expire'=>$time+self::SMSEXPIRE , 'verifyTime'=>0);
				Yii::app()->session['smsCode']		= $session;
				
				$this->jsonOutput(0 , array('vcode' => $verCode));
				Yii::app()->end();
			}
			#------------------------------------------ 测试结束  --------------------------------------------
			
			#------------------------------------------ PHP短信接口  --------------------------------------------
			if ($returned = SmsNote::sendOne($form->phone , "您的验证码是{$verCode}，请于".(self::SMSEXPIRE/60)."分钟内正确输入"))
			{
				if (isset($returned['code']) && $returned['code'] == 0)
				{
					$session							= isset(Yii::app()->session['smsCode']) ? (array)Yii::app()->session['smsCode'] : array();
					$session[$sessKey]					= array('phone'=>$form->phone , 'verCode'=>$verCode , 'sendTime'=>$time , 'expire'=>$time+self::SMSEXPIRE , 'verifyTime'=>0);
					Yii::app()->session['smsCode']		= $session;
					
					$this->jsonOutput(0);
				}else{
					$this->jsonOutput($returned['code'] , $returned['message']);
				}
			}else{
				$this->jsonOutput(20 , '发送请求失败!');
			}
		}
		$this->jsonOutput(10 , $this->getFormError($form));
	}
	
	/**
	 * 全局公共模块 - 异步验证手机短信码
	 *
	 * @param		int			type		1=注册 , 2=找回密码 , 3=企业用户修改电话
	 * @param		bigint		phone		手机号码
	 * @param		int			code		手机短信码
	 */
	public function actionViferySmsCode()
	{
		$form			= ClassLoad::Only('GlobalForm');/* @var $form GlobalForm */
		$form->type		= (int)$this->getPost('type');
		$form->phone	= trim((string)$this->getPost('phone'));
		$form->code		= (int)$this->getPost('code');
		$session		= Yii::app()->session;
		
		if ($form->viferySmsCode($this->sessKey , empty($session['smsCode']) ? array() : (array)$session['smsCode']))
		{
			$sessKey							= $this->sessKey[$form->type];
			$sx									= new ArrayObject($session['smsCode']);
			$sx									= $sx->getArrayCopy();
			$sx[$sessKey]['verifyTime']			= time();
			Yii::app()->session['smsCode']		= $sx;
			
			$this->jsonOutput(0);
		}
		
		$this->jsonOutput(10 , $this->getFormError($form));
	}
	
	/**
	 * 全局公共模块 - 获取地区列表
	 * 
	 * @param		int			one			省ID
	 * @param		int			two			市ID
	 * @param		int			three		县ID
	 */
	public function actionDict()
	{
		$form			= ClassLoad::Only('GlobalForm');/* @var $form GlobalForm */
		$form->one		= (int)$this->getPost('one');
		$form->two		= (int)$this->getPost('two');
		$form->three	= (int)$this->getPost('three');
		
		if ($form->viferyDict(false))
			$this->jsonOutput(0 , GlobalDict::getUnidList($form->one , $form->two , $form->three));
		
		$this->jsonOutput(10 , $this->getFormError($form));
	}
	
	/**
	 * 全局公共模块 - 根据存在的ID获得地区
	 * 
	 * @param		int			one			省ID
	 * @param		int			two			市ID
	 * @param		int			three		县ID
	 */
	public function actionDictShow()
	{
		$form			= ClassLoad::Only('GlobalForm');/* @var $form GlobalForm */
		$form->one		= (int)$this->getPost('one');
		$form->two		= (int)$this->getPost('two');
		$form->three	= (int)$this->getPost('three');
		
		if ($form->viferyDict(true))
		{
			$dict = array
			(
				'province'	=> GlobalDict::getUnidList(),
				'city'		=> GlobalDict::getUnidList($form->one),
				'county'	=> array(),
				'area'		=> array(),
			);
			
			if ($form->two > 0)
				$dict['county'] = GlobalDict::getUnidList($form->one , $form->two);
			
			if ($form->three > 0)
				$dict['area'] = GlobalDict::getUnidList($form->one , $form->two , $form->three);
			
			$this->jsonOutput(0 , $dict);
		}
		
		$this->jsonOutput(10 , $this->getFormError($form));
	}
}