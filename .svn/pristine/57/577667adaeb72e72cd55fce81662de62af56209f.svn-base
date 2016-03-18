<?php
/**
 * 个人
 * @author simon
 */
class MemberController extends WebController
{	
	public $showLeftNav = true;
	public $leftNavType = '';
	//验证码
	public function actions()
	{
		return array
		(
			'captcha' => array
			(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
				'minLength' => 4,
				'maxLength' => 6,
				'height' => 40,
				'width' => 150,
			),
			'page' => array('class' => 'CViewAction')
		);
	}
	//过滤器
	public function filters()
	{
		return array('checkLogin');
	}
	
	/**
	 * 检查是否登录
	 * @param	CFilterChain	$filterChain
	 */
	public function filterVerifyHeader(CFilterChain $filterChain)
	{
		if (!$this->getUid())
			$this->redirect($this->createFrontUrl('home/login'));
		
		$this->viewsUserID = $this->getUid();
		$this->viewsUserName = $this->getUser();
		$this->viewsUserName = empty($this->viewsUserName['nickname'])?(empty($this->viewsUserName['phone'])?'':$this->viewsUserName['phone']):$this->viewsUserName['nickname'];
		$filterChain->run();
	}
}