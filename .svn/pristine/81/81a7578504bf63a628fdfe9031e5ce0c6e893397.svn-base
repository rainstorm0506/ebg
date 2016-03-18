<?php
class MerchantController extends ApiController
{
	//短信验证有效期 5分钟
	const VALIDITYTIME = 300;
	const REGCODE = 'regs';
	const FINDCODE = 'find';
	
	//退出
	public function actionLogout()
	{
		Yii::app()->getUser()->logout();
		
		$session = Yii::app()->session;
		$session->open();
		$this->jsonOutput(0 , array('S_APISID' => $session->getSessionID()));
	}
	//忘记密码
	public function actionFindPassword()
	{
		$form = ClassLoad::Only('MerFindPassForm');/* @var $form MerFindPassForm */
		$form->outTime = self::VALIDITYTIME;
		$form->sessKey = self::FINDCODE;
		$form->attributes = empty($_POST) ? array() : $_POST;
		if ($form->validate())
		{
			$model = ClassLoad::Only('Merchants');/* @var $model Merchants */
			if ($model->findPassword($_POST))
			{
				$session = Yii::app()->session;
				$session->open();
				unset($session[$form->sessKey]);
				
				$this->jsonOutput(0);
			}else{
				$this->jsonOutput(2 , '注册失败!');
			}
		}
		$this->jsonOutput(1 , $this->getFormError($form));
	}
	
	/**
	 * 商家信息 - 注册
	 * 
	 * @param		bigint			phone		手机号码
	 * @param		password		string		密码，以字母开头，长度在6~18之间，只能包含字符、数字和下划线		(/^[a-zA-Z]\w{5,17}$/)
	 * @param		int				vcode		验证码
	 * @param		int				protocol	同意协议 , [0=不同意 , 1=同意]
	 * @param		int				apt			APP抛数据的时间
	 */
	public function actionSign()
	{
		$form = ClassLoad::Only('MerSignForm');/* @var $form MerSignForm */
		$form->outTime = self::VALIDITYTIME;
		$form->sessKey = self::REGCODE;
		$form->attributes = empty($_POST) ? array() : $_POST;
		if ($form->validate())
		{
			$model = ClassLoad::Only('Merchants');/* @var $model Merchants */
			if ($model->sign($_POST))
			{
				$session = Yii::app()->session;
				$session->open();
				unset($session[$form->sessKey]);
				
				$this->jsonOutput(0);
			}else{
				$this->jsonOutput(2 , '注册失败!');
			}
		}
		$this->jsonOutput(1 , $this->getFormError($form));
	}
	
	/**
	 * 商家信息 - 登录
	 * 
	 * @param		bigint		phone		手机号码
	 * @param		string		password	密码，以字母开头，长度在6~18之间，只能包含字符、数字和下划线		(/^[a-zA-Z]\w{5,17}$/)
	 */
	public function actionLogin()
	{
		$form = ClassLoad::Only('MerLoginForm');/* @var $form MerLoginForm */
		$form->attributes = empty($_POST) ? array() : $_POST;
		if ($form->validate() && $form->login())
		{
			$session = Yii::app()->session;
			$session->open();
			$user = $this->getUser();
			$user['S_APISID'] = $session->getSessionID();
			
			$user['qcode'] = '';
			$model = ClassLoad::Only('Merchants');/* @var $model Merchants */
			if(($info = $model->getMerInfo()) && !empty($info['user_code']))
				$user['qcode'] = Yii::app()->params['domain'].'asyn.qrcode?ucode='.urlencode($info['user_code']);
			$this->jsonOutput(0 , $user);
		}
		$this->jsonOutput(1 , $this->getFormError($form));
	}
	
	//发送验证码
	public function actionSendVcode()
	{
		$form = ClassLoad::Only('SendVocodeForm');/* @var $form SendVocodeForm */
		$form->attributes = empty($_POST) ? array() : $_POST;
		if ($form->validate())
		{
			$key = $form->type == 1 ? self::REGCODE : self::FINDCODE;
			$phone = $_POST['phone'];
			$verCode = mt_rand(10000 , 99999);
			#echo $verCode;
			
			if ((int)$this->getPost('test') === 1)
			{
				$session = Yii::app()->session;
				$session->open();
				$session[$key] = array('phone'=>$phone,'verCode'=>$verCode,'sendTime'=>time(),'verifyTime'=>0);
				$this->jsonOutput(0 , array('vcode'=>$verCode));
			}
			
			#$curl = ClassLoad::Only('Curl');/* @var $curl Curl */
			#$curl->postRequest($url);
			if ($returned = SmsNote::send(array($phone) , "您的验证码是{$verCode}，请于".floor(self::VALIDITYTIME/60)."分钟内正确输入"))
			{
				if (isset($returned['code']) && $returned['code'] == 0)
				{
					$session = Yii::app()->session;
					$session->open();
					$session[$key] = array('phone'=>$phone,'verCode'=>$verCode,'sendTime'=>time(),'verifyTime'=>0);
					$this->jsonOutput(0);
				}else{
					$this->jsonOutput($returned['code'] , $returned['message']);
				}
			}else{
				$this->jsonOutput(2 , '发送请求失败!');
			}
		}
		$this->jsonOutput(1 , $this->getFormError($form));
	}
	
	/**
	 * 商家信息及店铺信息
	 * 
	 * @param		post		int		apt			APP抛数据的时间
	 */
	 public function actionGetMerInfo()
	{
		$model= ClassLoad::Only('Merchants');/* @var $model Merchants */
		if($info = $model->getMerInfo())
		{
			$this->jsonOutput(0,$info);
		}else{
			$this->jsonOutput(2,'未找到信息！');
		}
		$this->jsonOutput(1, $this->getFormError($form));
	}
	
	/**
	 * 商家信息 - 获得用户等级
	 * 
	 * @param      post      int     apt         APP抛数据的时间
	 */
	public function actionGetLayer()
	{
		$form = ClassLoad::Only('PersonForm');/* @var $form PersonForm */
		$form->apt = $this->getPost('apt');
		if($form->validateShop())
		{
	    $model= ClassLoad::Only('Merchants');/* @var $model Merchants */
	    if(($info = $model->getUserInfoByID($this->getMerchantID())) && ($info = GlobalUser::getUserLayer($info['exp'] , 3)))
	    {
	        $this->jsonOutput(0 , $info);
	    }else{
	        $this->jsonOutput(2,'未找到信息！');
	    }
		}
	    $this->jsonOutput(1, $this->getFormError($form));
	}
	//修改头像
	public function actionModifyFace(){
		if(!$face=$this->getPost('face'))
			$this->jsonOutput(2,'参数有误');

		if(!$id=$this->getMerchantID())
			$this->jsonOutput(2,'请登录');

		$model= ClassLoad::Only('Merchants');/* @var $model Merchants */
		if($row=$model->modifyFace($id,$face)){
			GlobalMerchant::setReflushUser($this->getUser());
			$this->jsonOutput(0,$row);
		}else{
			$this->jsonOutput(2,'操作失败，请重试');
		}
	}
}