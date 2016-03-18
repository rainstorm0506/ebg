<?php
/**
 * 商家中心--订单管理--控制器
 * 
 * @author Jeson.Q
 */
class OrderController extends MerchantController
{
	//商家中心首页
	public function actionIndex()
	{
		$this->leftNavType = 'order';
		
		$form = ClassLoad::Only('OrderForm'); /* @var $form OrderForm */
		$model = ClassLoad::Only('Order'); /* @var $model Order */
		$searchPost = isset($_POST['OrderForm']) ? $_POST['OrderForm'] : array();

		// 设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($searchPost);
		$page = new CPagination();
		$page->pageVar = 'pagenum';
		$page->pageSize = 10;
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;

		//查询订单列表
		$orderDataAll = $model->getList($searchPost, $offset , $page->pageSize);
		$orderDataAll['express'] = $model->getExpressList();
		$orderDataAll['page'] = $page;
		$orderDataAll['form'] = $form;
		$orderDataAll['param'] = $searchPost;
		// 查询列表并 渲染试图
		$this->render('index' , $orderDataAll );
	}
	
	//订单详细页
	public function actionDetail()
	{
		$this->showLeftNav = false;
		$this->leftNavType = 'order';
		
		// 加载类 、检查数据	
		$order_sn = $this->getParam('oid');

		$model = ClassLoad::Only('Order'); /* @var $model Order */
		if (!$info = $model->getActiveInfo($order_sn))
			$this->error('订单不存在!');

		// 查询列表并 渲染试图
		$this->render('order_detail' , array(
			'info' 		=> $info,
			'express' 	=> $model->getExpressList()
		));
	}

	//操作订单 ---系列
	public function actionOptionOrder()
	{
		$isDetail = false;
		$info = '';
		if($_POST)
		{
			if(isset($_POST['detail'])){ 
				$isDetail = true;
				unset($_POST['detail']);
			}
			$order = ClassLoad::Only('Order');/* @var $order Order */
			$info = $order->setOrderInfo($_POST);
		}
		$this->redirect ( $isDetail ? array ('order/detail','oid' => $_POST['order_sn']) : array ('order/index') );
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

	//商家取消订单页面
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