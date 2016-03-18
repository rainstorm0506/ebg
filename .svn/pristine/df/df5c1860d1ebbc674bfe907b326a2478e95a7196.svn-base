<?php
class ApiController extends CController
{
	public $layout = 'main';
	
	private $S_APISID = '';				# 会话ID
	private $S_TOKEN = '';				# 每一次请求的随机码(32位)
	private $S_VECODE = '';				# 数据验证码(32位)
	private $S_VERSIONS = '';			# APP的版本号
	
// 	public function init()
// 	{
// 		parent::init();
		
// 		$model = ClassLoad::Only('ExtModels');/* @var $model ExtModels */
// 		$model->insert('yii_debug' , array(
// 			'route'		=> Yii::app()->getController()->getRoute(),
// 			'ip'		=> $_SERVER['REMOTE_ADDR'],
// 			'SID'		=> (string)$this->getPost('S_APISID'),
// 			'post'		=> json_encode($_POST),
// 			'time'		=> date('Y-m-d H:i:s'),
// 			'cookie'	=> json_encode($_COOKIE),
// 		));
// 	}
	
	//过滤器
	public function filters()
	{
		return array('checkLogin');
	}
	
	/**
	 * 检查是否登录
	 * @param	CFilterChain	$filterChain
	 */
	public function filterCheckLogin(CFilterChain $filterChain)
	{
		$this->S_APISID		= isset($_POST['S_APISID']) ? $_POST['S_APISID'] : '';
		$this->S_TOKEN		= isset($_POST['S_TOKEN']) ? $_POST['S_TOKEN'] : '';
		$this->S_VECODE		= isset($_POST['S_VECODE']) ? $_POST['S_VECODE'] : '';
		$this->S_VERSIONS	= isset($_POST['S_VERSIONS']) ? $_POST['S_VERSIONS'] : '';

		$session = Yii::app()->session;
		if ($this->S_APISID)
			$session->setSessionID($this->S_APISID);
		
		//免检登录方法
		$exemption = array(
			'device/init'				=> 1,
			'merchant/sign'				=> 1,
			'merchant/login'			=> 1,
			'merchant/findPassword'		=> 1,
			'merchant/sendVcode'		=> 1,
		);
		
		if (empty($exemption[Yii::app()->getController()->getRoute()]) && !$this->getUid())
			$this->jsonOutput(1 , '请登录!');
		
		$filterChain->run();
	}
	
	//获得登录用户ID , 这里是登录人ID , 所以 , 商家ID,子账号ID 都有可能
	public function getUid()
	{
		return (int)Yii::app()->getUser()->getId();
	}
	
	//获得登录人信息
	public function getUser()
	{
		return (($user = Yii::app()->getUser()->getName()) && is_array($user)) ? $user : array();
	}
	
	//获取商家ID
	public function getMerchantID()
	{
		if ($user = $this->getUser())
		{
			if ($user['user_type'] == 3)
			{
				return empty($user['merchant_id']) ? $user['id'] : $user['merchant_id'];
			}else{
				return $user['id'];
			}
		}
		return 0;
	}
	
	/**
	 * 获取 get或者post . 优先get
	 * @param	string	$name			名称
	 * @param	mixed	$defaultValue	默认值
	 */
	public function getParam($name , $defaultValue = null)
	{
		return Yii::app()->getRequest()->getParam($name , $defaultValue);
	}
	
	/**
	 * 获取 post
	 * @param	string	$name			名称
	 * @param	mixed	$defaultValue	默认值
	 */
	public function getPost($name , $defaultValue = null)
	{
		return Yii::app()->getRequest()->getPost($name , $defaultValue);
	}
	
	/**
	 * 获取 get
	 * @param	string	$name			名称
	 * @param	mixed	$defaultValue	默认值
	 */
	public function getQuery($name , $defaultValue = null)
	{
		return Yii::app()->getRequest()->getQuery($name , $defaultValue);
	}
	
	/**
	 * 请求是否是post
	 */
	public function isPost()
	{
		return Yii::app()->getRequest()->isPostRequest;
	}
	
	public function getFormError(CFormModel $form)
	{
		$formError = array();
		foreach ($form->getErrors() as $v)
		{
			if (is_string($v))
			{
				$formError[md5($v)] = $v;
			}elseif (is_array($v)){
				foreach ($v as $bv)
					$formError[md5($bv)] = $bv;
			}
		}
		$form->clearErrors();
		return array_values($formError);
	}
	
	/**
	 * 设置API返回值 直接输出
	 * @param	int				$code		错误码
	 * @param	string/array	$mixed		错误信息 / 正常情况下输出的数据
	 */
	public function jsonOutput($code , $mixed = array())
	{
		$return = array('code'=>$code , 'message'=>array() , 'data'=>array());
		
		if ((int)$this->getPost('debug') == 1)
		{
			$return['data'] = $_POST;
		}else{
			if ($code === 0)
			{
				$return['data'] = is_array($mixed) ? $mixed : array();
			}else{
				$return['message'] = is_string($mixed) ? array($mixed) : $mixed;
			}
		}
		$return['data'] = empty($return['data']) ? null : $return['data'];
		$return['message'] = empty($return['message']) ? '' : join('###', $return['message']);
		Yii::app()->end(json_encode($return));
	}
}