<?php
/**
 * 企业中心--首页 - 控制器
 * 
 * @author Jeson.Q
 */
class HomeController extends EnterpriseController
{
	//登录到用户中心
	public function actionIndex()
	{
		//如果已登录,跳转到默认登录后页面
		if ($this->getUid()){
			$this->redirect($this->createFrontUrl('enterprise/order'));
		}else{
			$this->redirect($this->createFrontUrl('home/login'));
		}
	}
}