<?php
/**
 * 商家中心--店铺订单数据统计--控制器
 * 
 * @author Jeson.Q
 */
class StoreHomeController extends ApiController
{
	//商家中心--订单数据统计
	public function actionStoreData()
	{
		$storeDataAll = array();
		$form = ClassLoad::Only('OrderForm'); 		/* @var $form OrderForm */
		$model = ClassLoad::Only('Store'); 			/* @var $model Store */
		
		$apt = (int)$this->getPost('apt');
		$uid = (int)$this->getMerchantID();
		
		$parms = array('apt' => $apt);
		$form->optionType = 'express';
		$form->attributes = $parms;
		if($form->validate()){
			//统计商家订单数据
			$storeDataAll = $model->getStoreOrderData($uid);
			$this->jsonOutput(0, $storeDataAll);
		}
		// 接口传入数据验证有误
		$this->jsonOutput(1, $this->getFormError($form));
	}

}