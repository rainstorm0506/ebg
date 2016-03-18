<?php
/**
 * 企业
 * @author jeson.Q
 */
class EnterpriseController extends WebController
{
	public $showLeftNav = true;
	public $leftNavType = '';
	
	//自动加载判断
	public function init()
	{
		$userInfo = $this->getUser();
		if(!isset($userInfo['id'])){
			$this->redirect($this->createFrontUrl('home/login'));
			return false;
		}
		switch ((int)$userInfo['user_type']){
			case 1 : $this->redirect($this->createFrontUrl('member/order'));break;
			case 3 : $this->redirect($this->createFrontUrl('merchant/order'));break;
		}
	}

	//过滤器
	public function filters()
	{
		parent::filters();
		
		return array('verifyHeader');
	}

	/**
	 * 检查是否登录
	 * @param	CFilterChain	$filterChain
	 */
	public function filterVerifyHeader(CFilterChain $filterChain)
	{
		if (!$this->getUid())
			$this->redirect($this->createFrontUrl('home/login'));
		
		$this->leftNavType = Yii::app()->getController()->getId();
		$filterChain->run();
	}
}