<?php
/**
 * 默认控制器 - 控制器
 * 
 * @author Jeson.Q
 */
class HomeController extends MemberController
{
	//登录到用户中心
	public function actionIndex()
	{
		//如果已登录,跳转到默认登录后页面
		if ($this->getUid()){
			$this->redirect($this->createFrontUrl('member/order'));
		}else{
			$this->redirect($this->createFrontUrl('home/login'));
		}
	}

}