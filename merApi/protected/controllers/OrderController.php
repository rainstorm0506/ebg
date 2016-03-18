<?php
/**
 * 商家中心--订单管理--控制器
 * 
 * @author Jeson.Q
 */
class OrderController extends ApiController
{
	//商家中心首页--订单列表
	public function actionOrderList()
	{
		$orderDataAll = array();
		$form = ClassLoad::Only('OrderForm'); 		/* @var $form OrderForm */
		$model = ClassLoad::Only('Order'); 			/* @var $model Order */
		
		$apt = (int)$this->getPost('apt');
		$status = (int)$this->getPost('status');	//筛选订单状态ID
		$pageNow = (int)$this->getPost('pageNow');	//筛选订单状态ID
		$pageSize = (int)$this->getPost('pageSize');//筛选订单状态ID
		$uid = (int)$this->getMerchantID();
		$parms = array(
			'apt' => $apt,
			'status' => $status
		);
		$form->optionType = 'list';
		$form->attributes = $parms;
		if($form->validate()){
			//查询订单列表
			$orderDataAll = $model->getList($uid, $status, $pageNow , empty($pageSize) ? 10 : $pageSize);
			$this->jsonOutput(0, $orderDataAll);
		}
		// 接口传入数据验证有误
		$this->jsonOutput(1, $this->getFormError($form));
	}

	//商家中心首页--订单列表
	public function actionOrderTotal()
	{
		$form = ClassLoad::Only('OrderForm'); 	/* @var $form OrderForm */
		$model = ClassLoad::Only('Order'); 		/* @var $model Order */

		$apt = (int)$this->getPost('apt');		//商家uid
		$status = (int)$this->getPost('status');//筛选订单状态ID
		$uid = (int)$this->getMerchantID();
		$param = array(
			'apt' => $apt,
			'status' => $status
		);
		$form->optionType = 'list';
		$form->attributes = $param;
		if($form->validate()){
			//查询订单相应状态下的总数
			$totalDatas = $model->getTotalNumber($uid, $status);
			$this->jsonOutput(0, $totalDatas);
		}
		// 接口传入数据验证有误
		$this->jsonOutput(1, $this->getFormError($form));
	}
	
	//订单详细页
	public function actionOrderDetail()
	{
		// 加载类 、检查数据	
		$orderDetail = array();
		$order_sn = (string)$this->getPost('oid');	//订单号
		$apt = (int)$this->getPost('apt');			//APP抛数据的时间
		$uid = (int)$this->getMerchantID();
		$model = ClassLoad::Only('Order');			/* @var $model Order */
		$form = ClassLoad::Only('OrderForm'); 		/* @var $form OrderForm */
		$param = array(
			'oid' => $order_sn,
			'apt' => $apt
		);
		$form->optionType = 'detail';
		$form->attributes = $param;
		if ($form->validate()){
			$orderDetail = $model->getActiveInfo($uid, $order_sn);
			if(empty($orderDetail)){
				$this->jsonOutput(1,'无该订单号数据！');
			}else{
				$this->jsonOutput(0, $orderDetail);
			}
		}
		// 接口传入数据验证有误
		$this->jsonOutput(1,$this->getFormError($form));
	}

	//查询所有物流公司信息
	public function actionExpressList()
	{
		// 加载类 、检查数据	
		$expressList = array();
		$apt = (int)$this->getPost('apt');			//APP抛数据的时间
		$uid = (int)$this->getUid();
		$model = ClassLoad::Only('Order');			/* @var $model Order */
		$form = ClassLoad::Only('OrderForm'); 		/* @var $form OrderForm */
		$param = array(
			'apt' => $apt
		);
		$form->optionType = 'express';
		$form->attributes = $param;
		if ($form->validate()){
			$expressList = $model->getExpressList($uid);
			if(empty($expressList)){
				$this->jsonOutput(1,'无相关物流公司数据！');
			}else{
				$this->jsonOutput(0, $expressList);
			}
		}
		// 接口传入数据验证有误
		$this->jsonOutput(1, '非法操作，程序终止！');
	}
	
	//操作订单 ---系列
	public function actionOptionOrder()
	{
		$info = '';
		if($_POST)
		{
			$model = ClassLoad::Only('Order');			/* @var $model Order */
			$form = ClassLoad::Only('OrderForm'); 		/* @var $form OrderForm */
			$param = array(
				'apt' => (int)$this->getPost('apt'),			//APP抛数据的时间
				'oid' => (string)$this->getPost('oid'),				//订单号
				'typename' => (string)$this->getPost('typename')	//操作类型名称
			);
			$form->optionType = 'option';
			$form->attributes = $param;
			if ($form->validate()){
				unset($_POST['apt']);unset($_POST['S_APISID']);
				$info['flag'] = $model->setOrderInfo($_POST);
				$this->jsonOutput(0, $info);
			}
		}
		// 接口传入数据验证有误
		$this->jsonOutput(1,$this->getFormError($form));
	}

	//商家删除订单操作
	public function actionDeleteOrder()
	{
		if($_POST)
		{
			$order = ClassLoad::Only('Order');/* @var $order Order */
			$order->deleteOrder($_POST);
		}
		$this->redirect (array (
			'order/index'
		));
	}

	//商家取消订单ajax 操作
	public function actionAjaxAbolish()
	{
		// 加载类 、检查数据
		$order_sn = $flag = null;
		$order_sn = $this->getParam('oid');
		$model = ClassLoad::Only('Order'); /* @var $model Order */
		
		if($_POST){
			if (!$orderInfo = $model->getActiveInfo($_POST['option_ordersn'])){
				echo 0;die('');
			}
			$flag = $model->setAbolishInfo($_POST);
			echo $flag ? 1 : 0;
		}else{
			echo 0;
		}	
	}
}