<?php
/**
 * 商家中心--首页--控制器 -
 * 
 * @author Jeson.Q
 */
class HomeController extends MerchantController
{
	//登录到用户中心
	public function actionIndex()
	{
		$this->leftNavType = 'home';
		//如果已登录,跳转到默认登录后页面
		if ($this->getMerchantID()){
			$model = ClassLoad::Only('Order'); /* @var $model Order */
			$id = (int)$this->getMerchantID();
			$allData = $model->getAllData($id);

			// 查询列表并 渲染试图
			$this->render('index' , array(
				'allData'	=>	$allData
			));

		}else{
			$this->redirect($this->createFrontUrl('home/login'));
		}
	}

}