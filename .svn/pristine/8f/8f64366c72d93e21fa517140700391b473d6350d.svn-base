<?php
/**
 * 企业中心--订单管理 - 控制器
 * 
 * @author Jeson.Q
 */
class OrderController extends EnterpriseController
{
	//企业中心首页
	public function actionIndex()
	{
		$this->leftNavType = 'order';
		
		$model = ClassLoad::Only('Order'); /* @var $model Order */
		$status = (int)$this->getQuery('status');
		
		// 设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($status);
		$page = new CPagination();
		$page->pageVar = 'pagenum';
		$page->pageSize = 10;
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;
		//查询订单列表
		$orderDataAll = $model->getList($status ? $status : 0,$offset , $page->pageSize);
		//获取全局状态表相关状态
		$statusList = $model->getStatusList(9);
		$orderLocal = !empty($orderDataAll)? array('orderList'	=>	isset($orderDataAll['orderList']) ? $orderDataAll['orderList'] : array(),'statusInfo'	=>	$orderDataAll['orderStatus']) : array();
		$orderLocal['statusList'] = $statusList;
		$orderLocal['page'] = $page;
		$orderLocal['status'] = $status;
		// 查询列表并 渲染试图
		$this->render('index' , $orderLocal );
	}
	
	//订单详细页
	public function actionDetail()
	{
		$this->showLeftNav = false;
		$this->leftNavType = 'order';
		
		// 加载类 、检查数据	
		if (!$id = (string)$this->getQuery('oid'))
			$this->error('错误的订单号');

		$model = ClassLoad::Only('Order'); /* @var $model Order */
		if (!$info = $model->getActiveInfo($id))
			$this->error('订单不存在!');

		// 查询列表并 渲染试图
		$this->render('order_detail' , array(
			'info' => $info,
		));
	}
	
	//单个已完成的订单---详细页
	public function actionOneOrderDetail()
	{
		$this->leftNavType = 'order';
	
		// 加载类 、检查数据
		if (!$id = (string)$this->getQuery('oid'))
			$this->error('错误的订单号');
	
		$model = ClassLoad::Only('Order'); /* @var $model Order */
		$orderInfo = $model->getActiveInfo($id);
	
		// 查询列表并 渲染试图
		$this->render('oneOrder' , array(
			'orderInfo' => $orderInfo,
		));
	}
	
	//拆分订单--详细页
	public function actionParent()
	{
		$this->showLeftNav = false;
		$this->leftNavType = 'order';
	
		// 加载类 、检查数据		
		$order = $orderInfo = array();
		$order_sn = $this->getParam('oid');
		if($order_sn){
			$model = ClassLoad::Only('Order'); /* @var $model Order */
			// 查询列表并 渲染试图
			$order = $model->getChildrenList($order_sn);
		}
		if (!$orderInfo = $model->getActiveInfo($order_sn))
			$this->error('订单不存在!');

		$this->render('order_split_detail' , array(
			'order' => $order,
			'order_sn' => $order_sn,
			'orderInfo' => $orderInfo,
		));
	}
	
	//企业用户查看拆分订单页面
	public function actionAbolishOrder()
	{
		$this->showLeftNav = false;
		$this->leftNavType = 'order';
		// 加载类 
		$order = $orderInfo = $statusList = array();
		$order_sn = $this->getParam('oid');
		$model = ClassLoad::Only('Order'); /* @var $model Order */
		if($order_sn){
			// 查询列表并 渲染试图
			$order = $model->getChildrenList($order_sn);
		}
		if (!$orderInfo = $model->getActiveInfo($order_sn))
			$this->error('订单不存在!');

		$this->render('order_split_detail' , array(
			'order' => $order,
			'order_sn' => $order_sn,
			'orderInfo' => $orderInfo
		));
	}

	//用户确认收货
	public function actionOptionOrder()
	{
		if($_POST)
		{
			$order = ClassLoad::Only('Order');/* @var $order Order */
			$order->setOrderInfo($_POST);
		}
		$this->redirect (array (
			'order/index'
		));
	}

	//用户删除订单操作
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

	//企业用户取消订单ajax 操作
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